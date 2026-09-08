package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.Entrega;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface EntregaRepository extends JpaRepository<Entrega, Integer> {

    List<Entrega> findAllByOrderByHoraLimiteAsc();

    List<Entrega> findByFkIdEmpleadoOrderByHoraLimiteAsc(Long fkIdEmpleado);

    List<Entrega> findByEstado(Entrega.Estado estado);

    long countByEstado(Entrega.Estado estado);
}
