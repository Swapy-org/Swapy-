package com.swapy.swapy_java.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

@Entity
@Table(name = "chats")
public class Chat {

    @Id
    @Column(name = "id_chat")
    private Integer idChat;

    @Column(name = "fk_id_doc", nullable = false)
    private Integer fkIdDoc;

    @Column(name = "fk_id_usuario", nullable = false)
    private Integer fkIdUsuario;

    @Column(name = "estado_chat")
    private String estadoChat;

    public Integer getIdChat() { return idChat; }
    public void setIdChat(Integer idChat) { this.idChat = idChat; }
    public Integer getFkIdDoc() { return fkIdDoc; }
    public void setFkIdDoc(Integer fkIdDoc) { this.fkIdDoc = fkIdDoc; }
    public Integer getFkIdUsuario() { return fkIdUsuario; }
    public void setFkIdUsuario(Integer fkIdUsuario) { this.fkIdUsuario = fkIdUsuario; }
    public String getEstadoChat() { return estadoChat; }
    public void setEstadoChat(String estadoChat) { this.estadoChat = estadoChat; }
}
