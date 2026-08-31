package com.empleados.Swapy.service;

import com.empleados.Swapy.model.UsuarioDenuncia;
import com.empleados.Swapy.repository.UsuarioDenunciaRepository;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;
import java.util.List;
import java.util.NoSuchElementException;

@Service
public class DenunciaService {

    private final UsuarioDenunciaRepository denunciaRepository;

    public DenunciaService(UsuarioDenunciaRepository denunciaRepository) {
        this.denunciaRepository = denunciaRepository;
    }

    public List<UsuarioDenuncia> listarTodas() {
        return denunciaRepository.findAllByOrderByFechaCreacionDesc();
    }

    public UsuarioDenuncia obtener(Integer id) {
        return denunciaRepository.findById(id)
                .orElseThrow(() -> new NoSuchElementException("Denuncia no encontrada: " + id));
    }

    public long contarPorEstado(UsuarioDenuncia.Estado estado) {
        return denunciaRepository.countByEstado(estado);
    }

    /** El empleado deja una respuesta y la pasa a "en_revision" si seguía pendiente. */
    public UsuarioDenuncia responder(Integer id, String respuesta) {
        UsuarioDenuncia denuncia = obtener(id);
        denuncia.setRespuesta(respuesta);
        denuncia.setFechaRespuesta(LocalDateTime.now());
        if (denuncia.getEstado() == UsuarioDenuncia.Estado.pendiente) {
            denuncia.setEstado(UsuarioDenuncia.Estado.en_revision);
        }
        return denunciaRepository.save(denuncia);
    }

    public UsuarioDenuncia marcarResuelta(Integer id) {
        UsuarioDenuncia denuncia = obtener(id);
        denuncia.setEstado(UsuarioDenuncia.Estado.resuelta);
        if (denuncia.getFechaRespuesta() == null) {
            denuncia.setFechaRespuesta(LocalDateTime.now());
        }
        return denunciaRepository.save(denuncia);
    }
}
