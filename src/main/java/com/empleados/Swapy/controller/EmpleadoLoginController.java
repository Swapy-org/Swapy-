package com.empleados.Swapy.controller;

import com.empleados.Swapy.config.SesionEmpleadoInterceptor;
import com.empleados.Swapy.model.Empleado;
import com.empleados.Swapy.service.EmpleadoAuthService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;

import java.util.Optional;

@Controller
public class EmpleadoLoginController {

    private final EmpleadoAuthService authService;

    public EmpleadoLoginController(EmpleadoAuthService authService) {
        this.authService = authService;
    }

    @GetMapping("/spempleados")
    public String mostrarLogin(HttpServletRequest request) {
        // Si ya hay sesión activa, va directo al panel.
        if (request.getSession(false) != null
                && request.getSession().getAttribute(SesionEmpleadoInterceptor.SESSION_KEY) != null) {
            return "redirect:/empleado/panel";
        }
        return "empleado/login";
    }

    @PostMapping("/spempleados")
    public String login(@RequestParam String correo,
                         @RequestParam String contrasena,
                         HttpServletRequest request,
                         Model model) {
        Optional<Empleado> empleadoOpt = authService.autenticar(correo, contrasena);
        if (empleadoOpt.isEmpty()) {
            model.addAttribute("error", "Correo o contraseña incorrectos.");
            return "empleado/login";
        }
        Empleado empleado = empleadoOpt.get();
        request.getSession(true).setAttribute(SesionEmpleadoInterceptor.SESSION_KEY, empleado.getIdEmpleado());
        return "redirect:/empleado/panel";
    }

    @PostMapping("/spempleados/logout")
    public String logout(HttpServletRequest request) {
        if (request.getSession(false) != null) {
            request.getSession().invalidate();
        }
        return "redirect:/spempleados";
    }
}
