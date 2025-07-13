<?php
session_start();
require_once __DIR__ . '/../functions/auth.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./views/loginview.php");
    exit();
}

$disabled = getDisabledState();
$btnClass = $disabled['class'];
$btnAttr = $disabled['attr'];
?>

<style>
    .reportes-container {
        padding: 40px 60px;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .titulo-reporte {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .card-filtros {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .formulario-reportes {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .fila-filtros {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .campo {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .campo label {
        font-size: 14px;
        font-weight: 500;
        color: #444;
        margin-bottom: 6px;
    }

    .input-filtro {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .input-filtro:focus {
        border-color: #0066cc;
        outline: none;
    }

    .fila-botones {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-generar,
    .btn-exportar {
        background-color: #0066cc;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-exportar {
        background-color: #28a745;
    }

    .btn-generar:hover {
        background-color: #0052a3;
    }

    .btn-exportar:hover {
        background-color: #218838;
    }

    .btn-generar.disabled-button,
    .btn-exportar.disabled-button {
        background-color: #aaa !important;
        cursor: not-allowed;
    }

    .alert-screen {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4caf50;
        color: white;
        padding: 14px 24px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        font-size: 15px;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
    }

    .alert-screen.show {
        opacity: 1;
    }

    .hidden {
        display: none;
    }
</style>

<section class="reportes-container">
    <h2 class="titulo-reporte">Generación de Reportes</h2>

    <div class="card-filtros">
        <form class="formulario-reportes" onsubmit="generarReporte(event); return false;">
            <div class="fila-filtros">
                <div class="campo">
                    <label for="fechaInicio">Desde</label>
                    <input type="date" id="fechaInicio" class="input-filtro" required>
                </div>
                <div class="campo">
                    <label for="fechaFin">Hasta</label>
                    <input type="date" id="fechaFin" class="input-filtro" required>
                </div>
            </div>

            <div class="fila-filtros">
                <div class="campo">
                    <label for="tipoReporte">Tipo de reporte</label>
                    <select id="tipoReporte" class="input-filtro">
                        <option value="ventas">Ventas</option>
                        <option value="inventario">Inventario</option>
                        <option value="clientes">Clientes</option>
                    </select>
                </div>
                <div class="campo">
                    <label for="estado">Estado</label>
                    <select id="estado" class="input-filtro">
                        <option value="">Todos</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
            </div>

            <div class="fila-botones">
                <input type="submit" value="Generar PDF" class="btn-generar <?= $btnClass ?>" <?= $btnAttr ?>>
                <input type="button" value="Exportar Excel" class="btn-exportar <?= $btnClass ?>" <?= $btnAttr ?> onclick="exportarExcel()">
            </div>
        </form>
    </div>

    <div id="alerta" class="alert-screen hidden">Reporte generado correctamente.</div>
</section>

<script>
    function generarReporte(e) {
        e.preventDefault();
        const btn = document.querySelector('.btn-generar');
        if (btn.classList.contains('disabled-button')) return;

        document.getElementById("alerta").classList.remove("hidden");
        setTimeout(() => {
            document.getElementById("alerta").classList.add("show");
            setTimeout(() => {
                document.getElementById("alerta").classList.remove("show");
                document.getElementById("alerta").classList.add("hidden");
            }, 3000);
        }, 100);
    }

    function exportarExcel() {
        const btn = document.querySelector('.btn-exportar');
        if (btn.classList.contains('disabled-button')) return;

        alert("Exportación de Excel en construcción...");
    }
</script>
