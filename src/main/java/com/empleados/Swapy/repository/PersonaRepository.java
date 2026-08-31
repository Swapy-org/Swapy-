package com.empleados.Swapy.repository;

import com.empleados.Swapy.model.Persona;
import org.springframework.data.jpa.repository.JpaRepository;

public interface PersonaRepository extends JpaRepository<Persona, Persona.PersonaId> {
}
