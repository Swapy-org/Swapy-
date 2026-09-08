package com.swapy.swapy_java.controller;

import com.swapy.swapy_java.config.SesionEmpleadoInterceptor;
import com.swapy.swapy_java.model.Empleado;
import com.swapy.swapy_java.repository.EmpleadoRepository;
import com.swapy.swapy_java.repository.PersonaRepository;
import jakarta.servlet.http.HttpSession;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Optional;

@Controller
@RequestMapping("/empleado")
public class EmpleadoController {

    @Autowired
    private EmpleadoRepository empleadoRepository;

    @Autowired
    private PersonaRepository personaRepository;

    private final BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    // 1. Muestra la vista del login (spempleados.html)
    @GetMapping("")
    public String index() {
        return "spempleados";
    }

    // 2. Procesa el Login
    @PostMapping("/login")
    public String login(@RequestParam("correo") String correo,
                        @RequestParam("password") String password,
                        HttpSession session,
                        RedirectAttributes redirectAttributes) {

        Optional<Empleado> empOpt = empleadoRepository.findByCorreo(correo);

        if (empOpt.isEmpty()) {
            redirectAttributes.addFlashAttribute("error", "El correo no existe en la tabla empleado.");
            return "redirect:/empleado";
        }

        Empleado emp = empOpt.get();
        boolean passwordValida = false;
        String hashBD = emp.getContrasena();

        if (hashBD != null && hashBD.startsWith("$2y$")) {
            hashBD = "$2a$" + hashBD.substring(4);
        }

        if (hashBD != null && (hashBD.startsWith("$2a$") || hashBD.startsWith("$2b$"))) {
            try {
                if (passwordEncoder.matches(password, hashBD)) {
                    passwordValida = true;
                }
            } catch (Exception ignored) {}
        }

        if (!passwordValida && password.equals(emp.getContrasena())) {
            passwordValida = true;
        }

        if (!passwordValida) {
            redirectAttributes.addFlashAttribute("error", "La contraseña es incorrecta.");
            return "redirect:/empleado";
        }

        String nombreCompleto = "Empleado";
        if (emp.getPersona() != null) {
            nombreCompleto = emp.getPersona().getNombreCompleto();
        }

        // Variables requeridas por la sesión y el interceptor
        session.setAttribute("isLoggedIn", true);
        session.setAttribute(SesionEmpleadoInterceptor.SESSION_KEY, emp.getIdEmpleado());
        session.setAttribute("empleadoId", emp.getIdEmpleado());
        session.setAttribute("id_empleado", emp.getIdEmpleado());
        session.setAttribute("id_doc", emp.getId() != null ? emp.getId().getPkfkIdDoc() : null);
        session.setAttribute("correo", emp.getCorreo());
        session.setAttribute("nombre", nombreCompleto);
        session.setAttribute("rol", "Empleado");

        // Redirige al EmpleadoPanelController
        return "redirect:/empleado/panel";
    }

    // 3. Logout
    @GetMapping("/logout")
    public String logout(HttpSession session) {
        session.invalidate();
        return "redirect:/empleado";
    }
}