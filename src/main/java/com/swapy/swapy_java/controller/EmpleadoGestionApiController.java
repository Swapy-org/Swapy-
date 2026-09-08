package com.swapy.swapy_java.controller;

import com.swapy.swapy_java.config.SesionEmpleadoInterceptor;
import com.swapy.swapy_java.model.Empleado;
import com.swapy.swapy_java.service.EmpleadoGestionService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;

@RestController
@RequestMapping("/api/empleado/gestion")
public class EmpleadoGestionApiController {

    private final EmpleadoGestionService gestionService;

    public EmpleadoGestionApiController(EmpleadoGestionService gestionService) {
        this.gestionService = gestionService;
    }

    private Long idEmpleadoSesion(HttpServletRequest request) {
        return (Long) request.getSession().getAttribute(SesionEmpleadoInterceptor.SESSION_KEY);
    }

    @PostMapping
    public ResponseEntity<?> crear(@RequestBody Map<String, String> body) {
        try {
            Empleado e = gestionService.crear(
                    Integer.valueOf(body.get("idTipoDoc")),
                    Long.valueOf(body.get("numeroDocumento")),
                    body.get("primerNombre"),
                    body.get("segundoNombre"),
                    body.get("primerApellido"),
                    body.get("segundoApellido"),
                    body.get("correo"),
                    body.get("contrasena")
            );
            return ResponseEntity.ok(Map.of("ok", true, "idEmpleado", e.getIdEmpleado()));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @PutMapping("/{idEmpleado}")
    public ResponseEntity<?> actualizar(@PathVariable Long idEmpleado, @RequestBody Map<String, String> body) {
        try {
            gestionService.actualizar(
                    idEmpleado,
                    body.get("primerNombre"),
                    body.get("segundoNombre"),
                    body.get("primerApellido"),
                    body.get("segundoApellido"),
                    body.get("correo")
            );
            return ResponseEntity.ok(Map.of("ok", true));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @DeleteMapping("/{idEmpleado}")
    public ResponseEntity<?> eliminar(@PathVariable Long idEmpleado, HttpServletRequest request) {
        try {
            gestionService.eliminar(idEmpleado, idEmpleadoSesion(request));
            return ResponseEntity.ok(Map.of("ok", true));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }
}
