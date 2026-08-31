package com.empleados.Swapy.model;

import jakarta.persistence.*;

@Entity
@Table(name = "recuperar_cuenta")
public class RecuperarCuenta {

    @Id
    @Column(name = "id_recuperar_cuenta")
    private Integer idRecuperarCuenta;

    @Column(name = "codigo_verif", nullable = false)
    private Integer codigoVerif;

    public Integer getIdRecuperarCuenta() { return idRecuperarCuenta; }
    public void setIdRecuperarCuenta(Integer idRecuperarCuenta) { this.idRecuperarCuenta = idRecuperarCuenta; }
    public Integer getCodigoVerif() { return codigoVerif; }
    public void setCodigoVerif(Integer codigoVerif) { this.codigoVerif = codigoVerif; }
}
