package com.swapy.swapy_java.config;

import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.config.annotation.InterceptorRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

@Configuration
public class WebConfig implements WebMvcConfigurer {

    @Override
    public void addInterceptors(InterceptorRegistry registry) {
        registry.addInterceptor(new SesionEmpleadoInterceptor())
                .addPathPatterns("/empleado/**", "/api/empleado/**")
                .excludePathPatterns(
                        "/empleado",        // Muestra el HTML del login (GET)
                        "/empleado/login"   // Procesa las credenciales (POST)
                );
    }
}