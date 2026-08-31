package com.empleados.Swapy.service;

import com.empleados.Swapy.model.Empleado;
import com.empleados.Swapy.repository.EmpleadoRepository;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class EmpleadoAuthService {

    private final EmpleadoRepository empleadoRepository;
    // Spring Security's BCrypt implementation entiende los hashes $2y$ que genera PHP (password_hash).
    private final BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    public EmpleadoAuthService(EmpleadoRepository empleadoRepository) {
        this.empleadoRepository = empleadoRepository;
    }

    /**
     * Valida las credenciales contra la tabla `empleado`.
     * @return el Empleado si las credenciales son correctas, vacío si no.
     */
    public Optional<Empleado> autenticar(String correo, String contrasenaPlano) {
        return empleadoRepository.findByCorreo(correo)
                .filter(emp -> passwordEncoder.matches(contrasenaPlano, emp.getContrasena()));
    }

    public boolean cambiarContrasena(Empleado empleado, String actual, String nueva) {
        if (!passwordEncoder.matches(actual, empleado.getContrasena())) {
            return false;
        }
        empleado.setContrasena(passwordEncoder.encode(nueva));
        empleadoRepository.save(empleado);
        return true;
    }
}
