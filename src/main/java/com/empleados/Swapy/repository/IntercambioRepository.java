package com.empleados.Swapy.repository;

import com.empleados.Swapy.model.Intercambio;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import java.time.LocalDate;
import java.util.List;

public interface IntercambioRepository extends JpaRepository<Intercambio, Integer> {

    List<Intercambio> findAllByOrderByFechaCierreDesc();

    long countByEstado(String estado);

    @Query("SELECT COUNT(i) FROM Intercambio i WHERE i.fechaCierre >= :desde")
    long countCreadosDesde(LocalDate desde);
}
