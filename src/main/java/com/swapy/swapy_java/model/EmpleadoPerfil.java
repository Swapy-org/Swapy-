package com.swapy.swapy_java.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

@Entity
@Table(name = "empleado_perfil")
public class EmpleadoPerfil {

    @Id
    @Column(name = "fk_id_empleado")
    private Long fkIdEmpleado;

    @Column(name = "telefono", length = 20)
    private String telefono;

    @Column(name = "ciudad", length = 60)
    private String ciudad;

    @Column(name = "medio_transporte", length = 40)
    private String medioTransporte;

    @Column(name = "cuenta_pago", length = 40)
    private String cuentaPago;

    public Long getFkIdEmpleado() { return fkIdEmpleado; }
    public void setFkIdEmpleado(Long fkIdEmpleado) { this.fkIdEmpleado = fkIdEmpleado; }
    public String getTelefono() { return telefono; }
    public void setTelefono(String telefono) { this.telefono = telefono; }
    public String getCiudad() { return ciudad; }
    public void setCiudad(String ciudad) { this.ciudad = ciudad; }
    public String getMedioTransporte() { return medioTransporte; }
    public void setMedioTransporte(String medioTransporte) { this.medioTransporte = medioTransporte; }
    public String getCuentaPago() { return cuentaPago; }
    public void setCuentaPago(String cuentaPago) { this.cuentaPago = cuentaPago; }
}
