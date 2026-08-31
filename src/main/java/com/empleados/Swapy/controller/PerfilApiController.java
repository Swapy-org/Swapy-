package com.empleados.Swapy.controller;

import com.empleados.Swapy.config.SesionEmpleadoInterceptor;
import com.empleados.Swapy.model.Empleado;
import com.empleados.Swapy.repository.EmpleadoRepository;
import com.empleados.Swapy.service.EmpleadoAuthService;
import com.empleados.Swapy.service.PerfilService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;

@RestController
@RequestMapping("/api/empleado/perfil")
public class PerfilApiController {

    private final PerfilService perfilService;
    private final EmpleadoAuthService authService;
    private final EmpleadoRepository empleadoRepository;

    public PerfilApiController(PerfilService perfilService, EmpleadoAuthService authService,
                                EmpleadoRepository empleadoRepository) {
        this.perfilService = perfilService;
        this.authService = authService;
        this.empleadoRepository = empleadoRepository;
    }

    private Empleado empleadoActual(HttpServletRequest request) {
        Long idEmpleado = (Long) request.getSession().getAttribute(SesionEmpleadoInterceptor.SESSION_KEY);
        return empleadoRepository.findByIdEmpleado(idEmpleado)
                .orElseThrow(() -> new IllegalStateException("Empleado no encontrado"));
    }

    @PostMapping("/actualizar")
    public ResponseEntity<?> actualizar(@RequestBody Map<String, String> body, HttpServletRequest request) {
        Empleado empleado = empleadoActual(request);
        perfilService.actualizarPerfil(
                empleado,
                body.get("nombreCompleto"),
                body.get("correo"),
                body.get("telefono"),
                body.get("ciudad"),
                body.get("medioTransporte")
        );
        return ResponseEntity.ok(Map.of("ok", true));
    }

    @PostMapping("/cambiar-contrasena")
    public ResponseEntity<?> cambiarContrasena(@RequestBody Map<String, String> body, HttpServletRequest request) {
        Empleado empleado = empleadoActual(request);
        boolean ok = authService.cambiarContrasena(empleado, body.get("actual"), body.get("nueva"));
        if (!ok) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", "La contraseña actual no es correcta."));
        }
        return ResponseEntity.ok(Map.of("ok", true));
    }
}
