package com.swapy.swapy_java.model;

import jakarta.persistence.*;

import java.io.Serializable;
import java.util.Objects;

@Entity
@Table(name = "empleado")
public class Empleado implements Serializable {

    @EmbeddedId
    private EmpleadoId id;

    @Column(name = "correo", length = 30, nullable = false)
    private String correo;

    @Column(name = "contrasena", length = 255, nullable = false)
    private String contrasena;

    // Relación de solo lectura hacia Persona: usa las MISMAS columnas físicas
    // que ya están mapeadas arriba en @EmbeddedId (pkfk_id_doc, id_empleado),
    // por eso va con insertable/updatable=false y sin @MapsId.
    @ManyToOne(fetch = FetchType.EAGER)
    @JoinColumns({
        @JoinColumn(name = "pkfk_id_doc", referencedColumnName = "fkpk_id_doc", insertable = false, updatable = false),
        @JoinColumn(name = "id_empleado", referencedColumnName = "documento", insertable = false, updatable = false)
    })
    private Persona persona;

    public Empleado() {}

    public EmpleadoId getId() { return id; }
    public void setId(EmpleadoId id) { this.id = id; }
    public String getCorreo() { return correo; }
    public void setCorreo(String correo) { this.correo = correo; }
    public String getContrasena() { return contrasena; }
    public void setContrasena(String contrasena) { this.contrasena = contrasena; }
    public Persona getPersona() { return persona; }
    public void setPersona(Persona persona) { this.persona = persona; }

    /** Atajo usado en toda la app para identificar al empleado (bigint id_empleado = documento). */
    public Long getIdEmpleado() { return id != null ? id.getIdEmpleado() : null; }

    @Embeddable
    public static class EmpleadoId implements Serializable {
        @Column(name = "pkfk_id_doc")
        private Integer pkfkIdDoc;

        @Column(name = "id_empleado")
        private Long idEmpleado;

        public EmpleadoId() {}
        public EmpleadoId(Integer pkfkIdDoc, Long idEmpleado) {
            this.pkfkIdDoc = pkfkIdDoc;
            this.idEmpleado = idEmpleado;
        }

        public Integer getPkfkIdDoc() { return pkfkIdDoc; }
        public void setPkfkIdDoc(Integer pkfkIdDoc) { this.pkfkIdDoc = pkfkIdDoc; }
        public Long getIdEmpleado() { return idEmpleado; }
        public void setIdEmpleado(Long idEmpleado) { this.idEmpleado = idEmpleado; }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (!(o instanceof EmpleadoId)) return false;
            EmpleadoId that = (EmpleadoId) o;
            return Objects.equals(pkfkIdDoc, that.pkfkIdDoc) && Objects.equals(idEmpleado, that.idEmpleado);
        }

        @Override
        public int hashCode() { return Objects.hash(pkfkIdDoc, idEmpleado); }
    }
}
