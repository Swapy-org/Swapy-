package com.swapy.swapy_java.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

import java.time.LocalDate;

@Entity
@Table(name = "intercambio")
public class Intercambio {

    @Id
    @Column(name = "id_intercambio")
    private Integer idIntercambio;

    @Column(name = "fk_id_chat", nullable = false)
    private Integer fkIdChat;

    @Column(name = "n_producto_publicado", length = 35)
    private String nProductoPublicado;

    @Column(name = "n_producto_ofertado", length = 35)
    private String nProductoOfertado;

    @Column(name = "fecha_cierre")
    private LocalDate fechaCierre;

    @Column(name = "fecha_entrega")
    private LocalDate fechaEntrega;

    @Column(name = "direccion", length = 255)
    private String direccion;

    /** Añadido por migración: 'En proceso' | 'Entregado' | 'Cancelado' */
    @Column(name = "estado", length = 20)
    private String estado = "En proceso";

    /** Añadido por migración: id_empleado (bigint) responsable, opcional. */
    @Column(name = "fk_id_empleado")
    private Long fkIdEmpleado;

    public Integer getIdIntercambio() { return idIntercambio; }
    public void setIdIntercambio(Integer idIntercambio) { this.idIntercambio = idIntercambio; }
    public Integer getFkIdChat() { return fkIdChat; }
    public void setFkIdChat(Integer fkIdChat) { this.fkIdChat = fkIdChat; }
    public String getNProductoPublicado() { return nProductoPublicado; }
    public void setNProductoPublicado(String nProductoPublicado) { this.nProductoPublicado = nProductoPublicado; }
    public String getNProductoOfertado() { return nProductoOfertado; }
    public void setNProductoOfertado(String nProductoOfertado) { this.nProductoOfertado = nProductoOfertado; }
    public LocalDate getFechaCierre() { return fechaCierre; }
    public void setFechaCierre(LocalDate fechaCierre) { this.fechaCierre = fechaCierre; }
    public LocalDate getFechaEntrega() { return fechaEntrega; }
    public void setFechaEntrega(LocalDate fechaEntrega) { this.fechaEntrega = fechaEntrega; }
    public String getDireccion() { return direccion; }
    public void setDireccion(String direccion) { this.direccion = direccion; }
    public String getEstado() { return estado; }
    public void setEstado(String estado) { this.estado = estado; }
    public Long getFkIdEmpleado() { return fkIdEmpleado; }
    public void setFkIdEmpleado(Long fkIdEmpleado) { this.fkIdEmpleado = fkIdEmpleado; }
}
