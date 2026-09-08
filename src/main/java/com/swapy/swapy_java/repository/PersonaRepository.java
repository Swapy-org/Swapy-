package com.swapy.swapy_java.repository;

import com.swapy.swapy_java.model.Persona;
import org.springframework.data.jpa.repository.JpaRepository;

public interface PersonaRepository extends JpaRepository<Persona, Persona.PersonaId> {
}
