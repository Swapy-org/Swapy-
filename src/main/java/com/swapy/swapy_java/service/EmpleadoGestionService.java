package com.swapy.swapy_java.service;

import com.swapy.swapy_java.model.*;
import com.swapy.swapy_java.repository.*;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.NoSuchElementException;

@Service
public class EmpleadoGestionService {

    private final EmpleadoRepository empleadoRepository;
    private final PersonaRepository personaRepository;
    private final RecuperarCuentaRepository recuperarCuentaRepository;
    private final BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    public EmpleadoGestionService(EmpleadoRepository empleadoRepository,
                                   PersonaRepository personaRepository,
                                   RecuperarCuentaRepository recuperarCuentaRepository) {
        this.empleadoRepository = empleadoRepository;
        this.personaRepository = personaRepository;
        this.recuperarCuentaRepository = recuperarCuentaRepository;
    }

    public List<Empleado> listarTodos() {
        return empleadoRepository.findAll();
    }

    /** Crea persona + recuperar_cuenta + empleado en una sola transacción. */
    @Transactional
    public Empleado crear(Integer idTipoDoc, Long numeroDocumento, String primerNombre, String segundoNombre,
                           String primerApellido, String segundoApellido, String correo, String contrasenaPlano) {

        if (empleadoRepository.findByIdEmpleado(numeroDocumento).isPresent()) {
            throw new IllegalArgumentException("Ya existe un empleado con ese número de documento.");
        }
        if (empleadoRepository.findByCorreo(correo).isPresent()) {
            throw new IllegalArgumentException("Ya existe un empleado con ese correo.");
        }

        // 1) recuperar_cuenta (persona.fk_id_recuperar_cuenta es NOT NULL)
        RecuperarCuenta recuperarCuenta = new RecuperarCuenta();
        recuperarCuenta.setIdRecuperarCuenta(recuperarCuentaRepository.obtenerMaxId() + 1);
        recuperarCuenta.setCodigoVerif((int) (Math.random() * 900000) + 100000);
        recuperarCuentaRepository.save(recuperarCuenta);

        // 2) persona
        Persona persona = new Persona();
        persona.setId(new Persona.PersonaId(idTipoDoc, numeroDocumento));
        persona.setPrimerNombre(primerNombre);
        persona.setSegundoNombre(segundoNombre);
        persona.setPrimerApellido(primerApellido);
        persona.setSegundoApellido(segundoApellido);
        persona.setFkIdRecuperarCuenta(recuperarCuenta.getIdRecuperarCuenta());
        personaRepository.save(persona);

        // 3) empleado
        Empleado empleado = new Empleado();
        empleado.setId(new Empleado.EmpleadoId(idTipoDoc, numeroDocumento));
        empleado.setCorreo(correo);
        empleado.setContrasena(passwordEncoder.encode(contrasenaPlano));
        return empleadoRepository.save(empleado);
    }

    @Transactional
    public Empleado actualizar(Long idEmpleado, String primerNombre, String segundoNombre,
                                String primerApellido, String segundoApellido, String correo) {
        Empleado empleado = obtener(idEmpleado);

        empleadoRepository.findByCorreo(correo).ifPresent(otro -> {
            if (!otro.getIdEmpleado().equals(idEmpleado)) {
                throw new IllegalArgumentException("Ese correo ya lo usa otro empleado.");
            }
        });

        Persona persona = empleado.getPersona();
        persona.setPrimerNombre(primerNombre);
        persona.setSegundoNombre(segundoNombre);
        persona.setPrimerApellido(primerApellido);
        persona.setSegundoApellido(segundoApellido);
        personaRepository.save(persona);

        empleado.setCorreo(correo);
        return empleadoRepository.save(empleado);
    }

    /** Solo borra el registro de `empleado`; se deja `persona` para no romper otras referencias. */
    public void eliminar(Long idEmpleado, Long idEmpleadoQueEjecuta) {
        if (idEmpleado.equals(idEmpleadoQueEjecuta)) {
            throw new IllegalStateException("No puedes eliminar tu propio usuario mientras tienes la sesión activa.");
        }
        Empleado empleado = obtener(idEmpleado);
        empleadoRepository.delete(empleado);
    }

    public Empleado obtener(Long idEmpleado) {
        return empleadoRepository.findByIdEmpleado(idEmpleado)
                .orElseThrow(() -> new NoSuchElementException("Empleado no encontrado: " + idEmpleado));
    }
}
