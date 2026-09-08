package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.RecuperarCuenta;
import org.springframework.data.jpa.repository.JpaRepository;

public interface RecuperarCuentaRepository extends JpaRepository<RecuperarCuenta, Integer> {

    @org.springframework.data.jpa.repository.Query("SELECT COALESCE(MAX(r.idRecuperarCuenta), 0) FROM RecuperarCuenta r")
    Integer obtenerMaxId();
}
