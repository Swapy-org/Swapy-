package com.empleados.Swapy.repository;

import com.empleados.Swapy.model.RecuperarCuenta;
import org.springframework.data.jpa.repository.JpaRepository;

public interface RecuperarCuentaRepository extends JpaRepository<RecuperarCuenta, Integer> {

    @org.springframework.data.jpa.repository.Query("SELECT COALESCE(MAX(r.idRecuperarCuenta), 0) FROM RecuperarCuenta r")
    Integer obtenerMaxId();
}
