package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.Empleado;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.Optional;

public interface EmpleadoRepository extends JpaRepository<Empleado, Empleado.EmpleadoId> {
    Optional<Empleado> findByCorreo(String correo);

    @Query("SELECT e FROM Empleado e WHERE e.id.idEmpleado = :idEmpleado")
    Optional<Empleado> findByIdEmpleado(@Param("idEmpleado") Long idEmpleado);
}

