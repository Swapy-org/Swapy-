package com.swapy.swapy_java.model;

import jakarta.persistence.*;

import java.time.LocalDateTime;
import java.time.LocalTime;

@Entity
@Table(name = "entrega")
public class Entrega {

    public enum Estado { Pendiente, Aceptada, En_ruta, Entregada, Fallida, Cancelada }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_entrega")
    private Integer idEntrega;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "fk_id_intercambio", nullable = false)
    private Intercambio intercambio;

    @Column(name = "fk_id_empleado")
    private Long fkIdEmpleado;

    @Column(name = "solicitante", length = 100)
    private String solicitante;

    @Column(name = "direccion_recogida", nullable = false, length = 255)
    private String direccionRecogida;

    @Column(name = "direccion_entrega", nullable = false, length = 255)
    private String direccionEntrega;

    @Column(name = "hora_limite")
    private LocalTime horaLimite;

    @Enumerated(EnumType.STRING)
    @Column(name = "estado")
    private Estado estado = Estado.Pendiente;

    @Column(name = "confirmacion_origen")
    private Boolean confirmacionOrigen = false;

    @Column(name = "confirmacion_destino")
    private Boolean confirmacionDestino = false;

    @Column(name = "intentos_fallidos")
    private Integer intentosFallidos = 0;

    @Column(name = "fecha_creacion", insertable = false, updatable = false)
    private LocalDateTime fechaCreacion;

    @Column(name = "fecha_actualizacion", insertable = false, updatable = false)
    private LocalDateTime fechaActualizacion;

    public Integer getIdEntrega() { return idEntrega; }
    public void setIdEntrega(Integer idEntrega) { this.idEntrega = idEntrega; }
    public Intercambio getIntercambio() { return intercambio; }
    public void setIntercambio(Intercambio intercambio) { this.intercambio = intercambio; }
    public Long getFkIdEmpleado() { return fkIdEmpleado; }
    public void setFkIdEmpleado(Long fkIdEmpleado) { this.fkIdEmpleado = fkIdEmpleado; }
    public String getSolicitante() { return solicitante; }
    public void setSolicitante(String solicitante) { this.solicitante = solicitante; }
    public String getDireccionRecogida() { return direccionRecogida; }
    public void setDireccionRecogida(String direccionRecogida) { this.direccionRecogida = direccionRecogida; }
    public String getDireccionEntrega() { return direccionEntrega; }
    public void setDireccionEntrega(String direccionEntrega) { this.direccionEntrega = direccionEntrega; }
    public LocalTime getHoraLimite() { return horaLimite; }
    public void setHoraLimite(LocalTime horaLimite) { this.horaLimite = horaLimite; }
    public Estado getEstado() { return estado; }
    public void setEstado(Estado estado) { this.estado = estado; }
    public Boolean getConfirmacionOrigen() { return confirmacionOrigen; }
    public void setConfirmacionOrigen(Boolean confirmacionOrigen) { this.confirmacionOrigen = confirmacionOrigen; }
    public Boolean getConfirmacionDestino() { return confirmacionDestino; }
    public void setConfirmacionDestino(Boolean confirmacionDestino) { this.confirmacionDestino = confirmacionDestino; }
    public Integer getIntentosFallidos() { return intentosFallidos; }
    public void setIntentosFallidos(Integer intentosFallidos) { this.intentosFallidos = intentosFallidos; }
    public LocalDateTime getFechaCreacion() { return fechaCreacion; }
    public LocalDateTime getFechaActualizacion() { return fechaActualizacion; }
}
