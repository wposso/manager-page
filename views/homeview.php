<h2 class="dashboard-title">Panel de Control</h2>
<p class="dashboard-subtitle">Resumen general del sistema:</p>

<div class="dashboard-scrollable">
  <div class="dashboard-cards">
      <div class="card"><h3>Total Categorías</h3><p>7</p></div>
      <div class="card"><h3>Proveedores Activos</h3><p>10</p></div>
      <div class="card"><h3>Movimientos Hoy</h3><p>24</p></div>
      <div class="card"><h3>Novedades Recientes</h3><p>5</p></div>
  </div>

  <div class="chart-section">
      <h3>Movimientos de Inventario (últimos 6 meses)</h3>
      <canvas id="barChart" height="100"></canvas>
  </div>

  <div class="chart-section" style="margin-top: 30px;">
      <h3>Distribución por Categoría</h3>
      <canvas id="pieChart" height="100"></canvas>
  </div>
</div>

<style>
  .dashboard-title {
      font-size: 28px;
      margin-bottom: 5px;
      font-weight: 600;
      font-family: 'Inter', sans-serif;
  }

  .dashboard-subtitle {
      font-size: 15px;
      color: #666;
      margin-bottom: 20px;
      font-family: 'Inter', sans-serif;
  }

  .dashboard-scrollable {
      max-height: calc(100vh - 120px); /* ajusta según la altura de tu header */
      overflow-y: auto;
      padding-right: 10px;
  }

  .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
  }

  .card {
      background-color: #ffffff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
      transition: transform 0.2s ease-in-out;
  }

  .card:hover {
      transform: translateY(-4px);
  }

  .card h3 {
      font-size: 14px;
      font-weight: 500;
      color: #888;
      margin-bottom: 8px;
  }

  .card p {
      font-size: 26px;
      font-weight: bold;
      color: #2d8f2d;
      margin: 0;
  }

  .chart-section {
      background-color: #ffffff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
      margin-bottom: 30px;
  }

  .chart-section h3 {
      margin-bottom: 20px;
      font-size: 18px;
      color: #444;
      font-family: 'Inter', sans-serif;
  }
</style>
