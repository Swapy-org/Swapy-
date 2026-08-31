package com.empleados.Swapy.controller;

import com.empleados.Swapy.config.SesionEmpleadoInterceptor;
import com.empleados.Swapy.model.Entrega;
import com.empleados.Swapy.service.EntregaService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;

@RestController
@RequestMapping("/api/empleado/entregas")
public class EntregaApiController {

    private final EntregaService entregaService;

    public EntregaApiController(EntregaService entregaService) {
        this.entregaService = entregaService;
    }

    private Long idEmpleado(HttpServletRequest request) {
        return (Long) request.getSession().getAttribute(SesionEmpleadoInterceptor.SESSION_KEY);
    }

    @PostMapping("/{id}/aceptar")
    public ResponseEntity<?> aceptar(@PathVariable Integer id, HttpServletRequest request) {
        try {
            Entrega e = entregaService.aceptar(id, idEmpleado(request));
            return ResponseEntity.ok(Map.of("ok", true, "estado", e.getEstado()));
        } catch (IllegalStateException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @PostMapping("/{id}/rechazar")
    public ResponseEntity<?> rechazar(@PathVariable Integer id) {
        Entrega e = entregaService.rechazar(id);
        return ResponseEntity.ok(Map.of("ok", true, "estado", e.getEstado()));
    }

    @PostMapping("/{id}/en-ruta")
    public ResponseEntity<?> enRuta(@PathVariable Integer id, HttpServletRequest request) {
        try {
            Entrega e = entregaService.marcarEnRuta(id, idEmpleado(request));
            return ResponseEntity.ok(Map.of("ok", true, "estado", e.getEstado()));
        } catch (IllegalStateException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @PostMapping("/{id}/entregada")
    public ResponseEntity<?> entregada(@PathVariable Integer id, HttpServletRequest request) {
        try {
            Entrega e = entregaService.marcarEntregada(id, idEmpleado(request));
            return ResponseEntity.ok(Map.of("ok", true, "estado", e.getEstado()));
        } catch (IllegalStateException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @PostMapping("/{id}/intento-fallido")
    public ResponseEntity<?> intentoFallido(@PathVariable Integer id, HttpServletRequest request) {
        try {
            Entrega e = entregaService.marcarIntentoFallido(id, idEmpleado(request));
            return ResponseEntity.ok(Map.of("ok", true, "estado", e.getEstado(), "intentos", e.getIntentosFallidos()));
        } catch (IllegalStateException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    // ===================== CRUD de solicitudes de entrega =====================

    @PostMapping
    public ResponseEntity<?> crear(@RequestBody Map<String, String> body) {
        try {
            Entrega e = entregaService.crear(
                    Integer.valueOf(body.get("idIntercambio")),
                    body.get("solicitante"),
                    body.get("direccionRecogida"),
                    body.get("direccionEntrega"),
                    body.get("horaLimite") != null && !body.get("horaLimite").isBlank()
                            ? java.time.LocalTime.parse(body.get("horaLimite")) : null
            );
            return ResponseEntity.ok(Map.of("ok", true, "id", e.getIdEntrega()));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @PutMapping("/{id}")
    public ResponseEntity<?> actualizar(@PathVariable Integer id, @RequestBody Map<String, String> body) {
        try {
            entregaService.actualizar(
                    id,
                    body.get("solicitante"),
                    body.get("direccionRecogida"),
                    body.get("direccionEntrega"),
                    body.get("horaLimite") != null && !body.get("horaLimite").isBlank()
                            ? java.time.LocalTime.parse(body.get("horaLimite")) : null
            );
            return ResponseEntity.ok(Map.of("ok", true));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<?> eliminar(@PathVariable Integer id) {
        try {
            entregaService.eliminar(id);
            return ResponseEntity.ok(Map.of("ok", true));
        } catch (RuntimeException ex) {
            return ResponseEntity.badRequest().body(Map.of("ok", false, "error", ex.getMessage()));
        }
    }
}
