package com.empleados.Swapy.config;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import org.springframework.web.servlet.HandlerInterceptor;

/**
 * Protege /empleado/** : exige que exista un empleado logueado en sesión.
 * Si la petición es de tipo fetch/AJAX responde 401 en vez de redirigir,
 * para que el JS del panel pueda manejarlo.
 */
public class SesionEmpleadoInterceptor implements HandlerInterceptor {

    public static final String SESSION_KEY = "EMPLEADO_ID";

    @Override
    public boolean preHandle(HttpServletRequest request, HttpServletResponse response, Object handler) throws Exception {
        HttpSession session = request.getSession(false);
        boolean autenticado = session != null && session.getAttribute(SESSION_KEY) != null;
        if (autenticado) {
            return true;
        }

        String requestedWith = request.getHeader("X-Requested-With");
        if ("XMLHttpRequest".equals(requestedWith) || request.getRequestURI().contains("/api/")) {
            response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
            return false;
        }

        response.sendRedirect(request.getContextPath() + "/spempleados");
        return false;
    }
}
