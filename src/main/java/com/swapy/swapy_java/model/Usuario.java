package com.swapy.swapy_java.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

@Entity
@Table(name = "usuario")
public class Usuario {

    @Id
    @Column(name = "id_usuario")
    private Integer idUsuario;

    @Column(name = "username", length = 50)
    private String username;

    @Column(name = "correo", length = 50, nullable = false)
    private String correo;

    // NUEVO CAMPO AGREGADO
    @Column(name = "numero_documento", length = 30)
    private String numeroDocumento;

    @Column(name = "nombre", length = 60)
    private String nombre;

    @Column(name = "rol", length = 50)
    private String rol;

    @Column(name = "estado", length = 20)
    private String estado;

    // --- GETTERS Y SETTERS ---

    public Integer getIdUsuario() { return idUsuario; }
    public void setIdUsuario(Integer idUsuario) { this.idUsuario = idUsuario; }

    public String getUsername() { return username; }
    public void setUsername(String username) { this.username = username; }

    public String getCorreo() { return correo; }
    public void setCorreo(String correo) { this.correo = correo; }

    // GETTER Y SETTER PARA EL NUEVO CAMPO
    public String getNumeroDocumento() { return numeroDocumento; }
    public void setNumeroDocumento(String numeroDocumento) { this.numeroDocumento = numeroDocumento; }

    public String getNombre() { return nombre; }
    public void setNombre(String nombre) { this.nombre = nombre; }

    public String getRol() { return rol; }
    public void setRol(String rol) { this.rol = rol; }

    public String getEstado() { return estado; }
    public void setEstado(String estado) { this.estado = estado; }

    /** Nombre para mostrar: usa `nombre`, si no hay usa `username`, si no el correo. */
    public String getNombreParaMostrar() {
        if (nombre != null && !nombre.isBlank()) return nombre;
        if (username != null && !username.isBlank()) return username;
        return correo;
    }
}