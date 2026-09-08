package com.swapy.swapy_java.model;

import jakarta.persistence.*;

import java.time.LocalDateTime;

@Entity
@Table(name = "usuario_denuncias")
public class UsuarioDenuncia {

    public enum Estado { pendiente, en_revision, resuelta, rechazada }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_denuncia")
    private Integer idDenuncia;

    @Column(name = "id_usuario_denunciante", nullable = false)
    private Integer idUsuarioDenunciante;

    @Column(name = "id_usuario_denunciado")
    private Integer idUsuarioDenunciado;

    @Column(name = "tipo_denuncia", nullable = false, length = 100)
    private String tipoDenuncia;

    @Column(name = "descripcion", nullable = false, columnDefinition = "LONGTEXT")
    private String descripcion;

    @Column(name = "respuesta", columnDefinition = "LONGTEXT")
    private String respuesta;

    @Enumerated(EnumType.STRING)
    @Column(name = "estado")
    private Estado estado = Estado.pendiente;

    @Column(name = "fecha_creacion", insertable = false, updatable = false)
    private LocalDateTime fechaCreacion;

    @Column(name = "fecha_respuesta")
    private LocalDateTime fechaRespuesta;

    public Integer getIdDenuncia() { return idDenuncia; }
    public void setIdDenuncia(Integer idDenuncia) { this.idDenuncia = idDenuncia; }
    public Integer getIdUsuarioDenunciante() { return idUsuarioDenunciante; }
    public void setIdUsuarioDenunciante(Integer idUsuarioDenunciante) { this.idUsuarioDenunciante = idUsuarioDenunciante; }
    public Integer getIdUsuarioDenunciado() { return idUsuarioDenunciado; }
    public void setIdUsuarioDenunciado(Integer idUsuarioDenunciado) { this.idUsuarioDenunciado = idUsuarioDenunciado; }
    public String getTipoDenuncia() { return tipoDenuncia; }
    public void setTipoDenuncia(String tipoDenuncia) { this.tipoDenuncia = tipoDenuncia; }
    public String getDescripcion() { return descripcion; }
    public void setDescripcion(String descripcion) { this.descripcion = descripcion; }
    public String getRespuesta() { return respuesta; }
    public void setRespuesta(String respuesta) { this.respuesta = respuesta; }
    public Estado getEstado() { return estado; }
    public void setEstado(Estado estado) { this.estado = estado; }
    public LocalDateTime getFechaCreacion() { return fechaCreacion; }
    public LocalDateTime getFechaRespuesta() { return fechaRespuesta; }
    public void setFechaRespuesta(LocalDateTime fechaRespuesta) { this.fechaRespuesta = fechaRespuesta; }
}
