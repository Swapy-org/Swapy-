package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.Usuario;
import org.springframework.data.jpa.repository.JpaRepository;

public interface UsuarioRepository extends JpaRepository<Usuario, Integer> {
}
