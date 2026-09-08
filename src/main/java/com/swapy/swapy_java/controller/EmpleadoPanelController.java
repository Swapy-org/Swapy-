package com.swapy.swapy_java.controller;

import com.swapy.swapy_java.config.SesionEmpleadoInterceptor;
import com.swapy.swapy_java.model.Empleado;
import com.swapy.swapy_java.model.EmpleadoPerfil;
import com.swapy.swapy_java.repository.EmpleadoRepository;
import com.swapy.swapy_java.repository.TipoDocumentoRepository;
import com.swapy.swapy_java.service.*;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class EmpleadoPanelController {

    private final EmpleadoRepository empleadoRepository;
    private final TipoDocumentoRepository tipoDocumentoRepository;
    private final PanelEmpleadoService panelEmpleadoService;
    private final IntercambioService intercambioService;
    private final EntregaService entregaService;
    private final DenunciaService denunciaService;
    private final PerfilService perfilService;
    private final EmpleadoGestionService empleadoGestionService;

    public EmpleadoPanelController(EmpleadoRepository empleadoRepository,
                                    TipoDocumentoRepository tipoDocumentoRepository,
                                    PanelEmpleadoService panelEmpleadoService,
                                    IntercambioService intercambioService,
                                    EntregaService entregaService,
                                    DenunciaService denunciaService,
                                    PerfilService perfilService,
                                    EmpleadoGestionService empleadoGestionService) {
        this.empleadoRepository = empleadoRepository;
        this.tipoDocumentoRepository = tipoDocumentoRepository;
        this.panelEmpleadoService = panelEmpleadoService;
        this.intercambioService = intercambioService;
        this.entregaService = entregaService;
        this.denunciaService = denunciaService;
        this.perfilService = perfilService;
        this.empleadoGestionService = empleadoGestionService;
    }

    @GetMapping("/empleado/panel")
    public String panel(HttpServletRequest request, Model model) {
        Long idEmpleado = (Long) request.getSession().getAttribute(SesionEmpleadoInterceptor.SESSION_KEY);
        Empleado empleado = empleadoRepository.findByIdEmpleado(idEmpleado)
                .orElseThrow(() -> new IllegalStateException("Empleado de la sesión ya no existe"));
        EmpleadoPerfil perfil = perfilService.obtenerOCrear(idEmpleado);

        model.addAttribute("empleado", empleado);
        model.addAttribute("perfil", perfil);
        model.addAttribute("stats", panelEmpleadoService.obtenerStats(idEmpleado));
        model.addAttribute("intercambios", intercambioService.listarTodos());
        model.addAttribute("solicitudesPendientes", entregaService.listarPendientesSinAsignar());
        model.addAttribute("misEntregas", entregaService.listarMisEntregas(idEmpleado));
        model.addAttribute("todasLasEntregas", entregaService.listarTodas());
        model.addAttribute("denuncias", denunciaService.listarTodas());
        model.addAttribute("empleados", empleadoGestionService.listarTodos());
        model.addAttribute("tiposDocumento", tipoDocumentoRepository.findAll());

        return "panel";
    }
}
