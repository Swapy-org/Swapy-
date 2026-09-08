package com.swapy.swapy_java.service;

import com.swapy.swapy_java.model.Intercambio;
import com.swapy.swapy_java.repository.IntercambioRepository;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.util.List;

@Service
public class IntercambioService {

    private final IntercambioRepository intercambioRepository;

    public IntercambioService(IntercambioRepository intercambioRepository) {
        this.intercambioRepository = intercambioRepository;
    }

    public List<Intercambio> listarTodos() {
        return intercambioRepository.findAllByOrderByFechaCierreDesc();
    }

    public long contarPorEstado(String estado) {
        return intercambioRepository.countByEstado(estado);
    }

    public long contarCreadosEsteMes() {
        LocalDate inicioMes = LocalDate.now().withDayOfMonth(1);
        return intercambioRepository.countCreadosDesde(inicioMes);
    }
}
