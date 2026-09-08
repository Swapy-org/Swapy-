package com.swapy.swapy_java.model;

import jakarta.persistence.*;

import java.io.Serializable;
import java.util.Objects;

@Entity
@Table(name = "persona")
public class Persona implements Serializable {

    @EmbeddedId
    private PersonaId id;

    @Column(name = "primer_nombre", length = 15, nullable = false)
    private String primerNombre;

    @Column(name = "segundo_nombre", length = 15)
    private String segundoNombre;

    @Column(name = "primer_apellido", length = 12, nullable = false)
    private String primerApellido;

    @Column(name = "segundo_apellido", length = 12)
    private String segundoApellido;

    @Column(name = "fk_id_recuperar_cuenta", nullable = false)
    private Integer fkIdRecuperarCuenta;

    public Persona() {}

    public String getNombreCompleto() {
        StringBuilder sb = new StringBuilder();
        sb.append(primerNombre);
        if (segundoNombre != null && !segundoNombre.isBlank()) sb.append(" ").append(segundoNombre);
        sb.append(" ").append(primerApellido);
        if (segundoApellido != null && !segundoApellido.isBlank()) sb.append(" ").append(segundoApellido);
        return sb.toString();
    }

    public PersonaId getId() { return id; }
    public void setId(PersonaId id) { this.id = id; }
    public String getPrimerNombre() { return primerNombre; }
    public void setPrimerNombre(String primerNombre) { this.primerNombre = primerNombre; }
    public String getSegundoNombre() { return segundoNombre; }
    public void setSegundoNombre(String segundoNombre) { this.segundoNombre = segundoNombre; }
    public String getPrimerApellido() { return primerApellido; }
    public void setPrimerApellido(String primerApellido) { this.primerApellido = primerApellido; }
    public String getSegundoApellido() { return segundoApellido; }
    public void setSegundoApellido(String segundoApellido) { this.segundoApellido = segundoApellido; }
    public Integer getFkIdRecuperarCuenta() { return fkIdRecuperarCuenta; }
    public void setFkIdRecuperarCuenta(Integer fkIdRecuperarCuenta) { this.fkIdRecuperarCuenta = fkIdRecuperarCuenta; }

    @Embeddable
    public static class PersonaId implements Serializable {
        @Column(name = "fkpk_id_doc")
        private Integer fkpkIdDoc;

        @Column(name = "documento")
        private Long documento;

        public PersonaId() {}
        public PersonaId(Integer fkpkIdDoc, Long documento) {
            this.fkpkIdDoc = fkpkIdDoc;
            this.documento = documento;
        }

        public Integer getFkpkIdDoc() { return fkpkIdDoc; }
        public void setFkpkIdDoc(Integer fkpkIdDoc) { this.fkpkIdDoc = fkpkIdDoc; }
        public Long getDocumento() { return documento; }
        public void setDocumento(Long documento) { this.documento = documento; }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (!(o instanceof PersonaId)) return false;
            PersonaId that = (PersonaId) o;
            return Objects.equals(fkpkIdDoc, that.fkpkIdDoc) && Objects.equals(documento, that.documento);
        }

        @Override
        public int hashCode() { return Objects.hash(fkpkIdDoc, documento); }
    }
}
