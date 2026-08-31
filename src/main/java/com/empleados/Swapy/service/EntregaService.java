package com.empleados.Swapy.service;

import com.empleados.Swapy.model.Entrega;
import com.empleados.Swapy.model.Intercambio;
import com.empleados.Swapy.repository.EntregaRepository;
import com.empleados.Swapy.repository.IntercambioRepository;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDate;
import java.util.List;
import java.util.NoSuchElementException;

@Service
public class EntregaService {

    private final EntregaRepository entregaRepository;
    private final IntercambioRepository intercambioRepository;

    public EntregaService(EntregaRepository entregaRepository, IntercambioRepository intercambioRepository) {
        this.entregaRepository = entregaRepository;
        this.intercambioRepository = intercambioRepository;
    }

    public List<Entrega> listarPendientesSinAsignar() {
        return entregaRepository.findByEstado(Entrega.Estado.Pendiente);
    }

    public List<Entrega> listarMisEntregas(Long idEmpleado) {
        return entregaRepository.findByFkIdEmpleadoOrderByHoraLimiteAsc(idEmpleado);
    }

    public List<Entrega> listarTodas() {
        return entregaRepository.findAllByOrderByHoraLimiteAsc();
    }

    /** CREATE: nueva solicitud de entrega ligada a un intercambio existente. */
    @Transactional
    public Entrega crear(Integer idIntercambio, String solicitante, String direccionRecogida,
                          String direccionEntrega, java.time.LocalTime horaLimite) {
        Intercambio intercambio = intercambioRepository.findById(idIntercambio)
                .orElseThrow(() -> new NoSuchElementException("Intercambio no encontrado: " + idIntercambio));

        Entrega entrega = new Entrega();
        entrega.setIntercambio(intercambio);
        entrega.setSolicitante(solicitante);
        entrega.setDireccionRecogida(direccionRecogida);
        entrega.setDireccionEntrega(direccionEntrega);
        entrega.setHoraLimite(horaLimite);
        entrega.setEstado(Entrega.Estado.Pendiente);
        return entregaRepository.save(entrega);
    }

    /** UPDATE: solo se puede editar mientras sigue Pendiente (sin empleado asignado). */
    @Transactional
    public Entrega actualizar(Integer idEntrega, String solicitante, String direccionRecogida,
                               String direccionEntrega, java.time.LocalTime horaLimite) {
        Entrega entrega = obtener(idEntrega);
        if (entrega.getEstado() != Entrega.Estado.Pendiente) {
            throw new IllegalStateException("Solo se pueden editar solicitudes que siguen pendientes.");
        }
        entrega.setSolicitante(solicitante);
        entrega.setDireccionRecogida(direccionRecogida);
        entrega.setDireccionEntrega(direccionEntrega);
        entrega.setHoraLimite(horaLimite);
        return entregaRepository.save(entrega);
    }

    /** DELETE: solo si está Pendiente o ya Cancelada, para no borrar entregas en curso o completadas. */
    public void eliminar(Integer idEntrega) {
        Entrega entrega = obtener(idEntrega);
        if (entrega.getEstado() != Entrega.Estado.Pendiente && entrega.getEstado() != Entrega.Estado.Cancelada) {
            throw new IllegalStateException("Solo se pueden eliminar solicitudes pendientes o canceladas.");
        }
        entregaRepository.delete(entrega);
    }

    /** El empleado toma la solicitud pendiente: Pendiente -> Aceptada. */
    @Transactional
    public Entrega aceptar(Integer idEntrega, Long idEmpleado) {
        Entrega entrega = obtener(idEntrega);
        if (entrega.getEstado() != Entrega.Estado.Pendiente) {
            throw new IllegalStateException("La solicitud ya fue tomada por otro empleado.");
        }
        entrega.setFkIdEmpleado(idEmpleado);
        entrega.setEstado(Entrega.Estado.Aceptada);
        return entregaRepository.save(entrega);
    }

    public Entrega rechazar(Integer idEntrega) {
        Entrega entrega = obtener(idEntrega);
        entrega.setEstado(Entrega.Estado.Cancelada);
        return entregaRepository.save(entrega);
    }

    /** Aceptada -> En_ruta (el empleado recogió el producto y va en camino). */
    @Transactional
    public Entrega marcarEnRuta(Integer idEntrega, Long idEmpleado) {
        Entrega entrega = obtenerDeEmpleado(idEntrega, idEmpleado);
        entrega.setConfirmacionOrigen(true);
        entrega.setEstado(Entrega.Estado.En_ruta);
        return entregaRepository.save(entrega);
    }

    /** En_ruta -> Entregada. Requiere confirmación del cliente en destino. */
    @Transactional
    public Entrega marcarEntregada(Integer idEntrega, Long idEmpleado) {
        Entrega entrega = obtenerDeEmpleado(idEntrega, idEmpleado);
        entrega.setConfirmacionDestino(true);
        entrega.setEstado(Entrega.Estado.Entregada);
        entregaRepository.save(entrega);

        Intercambio intercambio = entrega.getIntercambio();
        intercambio.setEstado("Entregado");
        intercambio.setFechaEntrega(LocalDate.now());
        intercambioRepository.save(intercambio);
        return entrega;
    }

    /** Cliente no estaba en el punto de entrega: se reagenda automáticamente. */
    @Transactional
    public Entrega marcarIntentoFallido(Integer idEntrega, Long idEmpleado) {
        Entrega entrega = obtenerDeEmpleado(idEntrega, idEmpleado);
        entrega.setIntentosFallidos(entrega.getIntentosFallidos() + 1);
        // Se reagenda: vuelve a "En_ruta" salvo que ya haya fallado 3 veces, ahí se marca Fallida.
        if (entrega.getIntentosFallidos() >= 3) {
            entrega.setEstado(Entrega.Estado.Fallida);
        }
        return entregaRepository.save(entrega);
    }

    public Entrega obtener(Integer idEntrega) {
        return entregaRepository.findById(idEntrega)
                .orElseThrow(() -> new NoSuchElementException("Entrega no encontrada: " + idEntrega));
    }

    private Entrega obtenerDeEmpleado(Integer idEntrega, Long idEmpleado) {
        Entrega entrega = obtener(idEntrega);
        if (entrega.getFkIdEmpleado() == null || !entrega.getFkIdEmpleado().equals(idEmpleado)) {
            throw new IllegalStateException("Esta entrega no está asignada a este empleado.");
        }
        return entrega;
    }
}
