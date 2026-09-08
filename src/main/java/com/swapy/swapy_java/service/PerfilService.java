package com.swapy.swapy_java.service;

import com.swapy.swapy_java.model.Empleado;
import com.swapy.swapy_java.model.EmpleadoPerfil;
import com.swapy.swapy_java.model.Persona;
import com.swapy.swapy_java.repository.EmpleadoPerfilRepository;
import com.swapy.swapy_java.repository.EmpleadoRepository;
import jakarta.persistence.EntityManager;
import org.springframework.stereotype.Service;

@Service
public class PerfilService {

    private final EmpleadoPerfilRepository perfilRepository;
    private final EmpleadoRepository empleadoRepository;
    private final EntityManager entityManager;

    public PerfilService(EmpleadoPerfilRepository perfilRepository,
                          EmpleadoRepository empleadoRepository,
                          EntityManager entityManager) {
        this.perfilRepository = perfilRepository;
        this.empleadoRepository = empleadoRepository;
        this.entityManager = entityManager;
    }

    public EmpleadoPerfil obtenerOCrear(Long idEmpleado) {
        return perfilRepository.findById(idEmpleado).orElseGet(() -> {
            EmpleadoPerfil nuevo = new EmpleadoPerfil();
            nuevo.setFkIdEmpleado(idEmpleado);
            return nuevo;
        });
    }

    /** Actualiza nombre (persona), correo (empleado) y datos de contacto (empleado_perfil). */
    public void actualizarPerfil(Empleado empleado, String nombreCompleto, String correo,
                                  String telefono, String ciudad, String medioTransporte) {
        Persona persona = empleado.getPersona();
        if (nombreCompleto != null && !nombreCompleto.isBlank()) {
            String[] partes = nombreCompleto.trim().split("\\s+", 2);
            persona.setPrimerNombre(partes[0]);
            persona.setPrimerApellido(partes.length > 1 ? partes[1] : "");
            entityManager.merge(persona);
        }
        if (correo != null && !correo.isBlank()) {
            empleado.setCorreo(correo);
            empleadoRepository.save(empleado);
        }

        EmpleadoPerfil perfil = obtenerOCrear(empleado.getIdEmpleado());
        perfil.setTelefono(telefono);
        perfil.setCiudad(ciudad);
        perfil.setMedioTransporte(medioTransporte);
        perfilRepository.save(perfil);
    }
}
