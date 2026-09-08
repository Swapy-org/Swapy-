package com.swapy.swapy_java.service;

import com.swapy.swapy_java.model.Entrega;
import com.swapy.swapy_java.model.UsuarioDenuncia;
import org.springframework.stereotype.Service;

@Service
public class PanelEmpleadoService {

    private final IntercambioService intercambioService;
    private final EntregaService entregaService;
    private final DenunciaService denunciaService;

    public PanelEmpleadoService(IntercambioService intercambioService,
                                 EntregaService entregaService,
                                 DenunciaService denunciaService) {
        this.intercambioService = intercambioService;
        this.entregaService = entregaService;
        this.denunciaService = denunciaService;
    }

    public DashboardStats obtenerStats(Long idEmpleado) {
        DashboardStats stats = new DashboardStats();
        stats.intercambiosCreadosEsteMes = intercambioService.contarCreadosEsteMes();
        stats.intercambiosEntregados = intercambioService.contarPorEstado("Entregado");
        stats.intercambiosEnProceso = intercambioService.contarPorEstado("En proceso");
        stats.denunciasActivas = denunciaService.contarPorEstado(UsuarioDenuncia.Estado.pendiente)
                + denunciaService.contarPorEstado(UsuarioDenuncia.Estado.en_revision);
        stats.denunciasResueltas = denunciaService.contarPorEstado(UsuarioDenuncia.Estado.resuelta);
        stats.entregasPendientesHoy = entregaService.listarMisEntregas(idEmpleado).stream()
                .filter(e -> e.getEstado() == Entrega.Estado.Aceptada || e.getEstado() == Entrega.Estado.En_ruta)
                .count();
        return stats;
    }

    public static class DashboardStats {
        public long intercambiosCreadosEsteMes;
        public long intercambiosEntregados;
        public long intercambiosEnProceso;
        public long denunciasActivas;
        public long denunciasResueltas;
        public long entregasPendientesHoy;
    }
}
