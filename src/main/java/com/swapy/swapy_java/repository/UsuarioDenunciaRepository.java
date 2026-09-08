package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.UsuarioDenuncia;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface UsuarioDenunciaRepository extends JpaRepository<UsuarioDenuncia, Integer> {

    List<UsuarioDenuncia> findAllByOrderByFechaCreacionDesc();

    long countByEstado(UsuarioDenuncia.Estado estado);
}
