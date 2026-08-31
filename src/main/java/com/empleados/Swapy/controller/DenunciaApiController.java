package com.empleados.Swapy.controller;

import com.empleados.Swapy.model.UsuarioDenuncia;
import com.empleados.Swapy.service.DenunciaService;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;

@RestController
@RequestMapping("/api/empleado/denuncias")
public class DenunciaApiController {

    private final DenunciaService denunciaService;

    public DenunciaApiController(DenunciaService denunciaService) {
        this.denunciaService = denunciaService;
    }

    @PostMapping("/{id}/responder")
    public ResponseEntity<?> responder(@PathVariable Integer id, @RequestBody Map<String, String> body) {
        UsuarioDenuncia d = denunciaService.responder(id, body.get("respuesta"));
        return ResponseEntity.ok(Map.of("ok", true, "estado", d.getEstado()));
    }

    @PostMapping("/{id}/resolver")
    public ResponseEntity<?> resolver(@PathVariable Integer id) {
        UsuarioDenuncia d = denunciaService.marcarResuelta(id);
        return ResponseEntity.ok(Map.of("ok", true, "estado", d.getEstado()));
    }
}
