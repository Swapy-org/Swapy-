package com.empleados.Swapy.model;

import jakarta.persistence.*;

@Entity
@Table(name = "t_doc")
public class TipoDocumento {

    @Id
    @Column(name = "id_doc")
    private Integer idDoc;

    @Column(name = "tipo_doc", length = 10, nullable = false)
    private String tipoDoc;

    public Integer getIdDoc() { return idDoc; }
    public void setIdDoc(Integer idDoc) { this.idDoc = idDoc; }
    public String getTipoDoc() { return tipoDoc; }
    public void setTipoDoc(String tipoDoc) { this.tipoDoc = tipoDoc; }
}
