function handleLoadView(viewPath) {
  const layout = document.getElementById("layout");
  const loader = document.getElementById("loader");

  loader.style.display = "flex";

  fetch("views/" + viewPath)
    .then((r) => r.text())
    .then((html) => {
      layout.innerHTML = html;

      if (
        viewPath === "homeview.php" &&
        typeof renderDashboardCharts === "function"
      ) {
        setTimeout(renderDashboardCharts, 50);
      }

      if (typeof initModalEventListeners === "function") {
        initModalEventListeners();
      }
    })
    .catch((err) => {
      layout.innerHTML = "<p>Error al cargar la vista.</p>";
      console.error(err);
    })
    .finally(() => {
      setTimeout(() => {
        loader.style.display = "none";
      }, 200);
    });
}

function getViewFromHash() {
  const hash = window.location.hash.slice(1); // quitar el #

  // Si viene alerta embebida, la separamos
  const partes = hash.split("::");
  const baseHash = partes.slice(0, 3).join("::");
  const alertaCodificada = partes.length > 3 ? partes[3] : null;

  // Si hay alerta, la guardamos para mostrar luego
  if (alertaCodificada) {
    try {
      const decoded = decodeURIComponent(alertaCodificada);
      const alerta = JSON.parse(decoded);
      if (alerta && alerta.title && alerta.message) {
        localStorage.setItem("alerta", JSON.stringify(alerta));
      }
    } catch (e) {
      console.warn("No se pudo decodificar alerta del hash.");
    }

    // Limpiamos la URL para evitar recarga futura con alerta
    setTimeout(() => {
      window.location.hash = baseHash;
    }, 100);
  }

  // Vista según hash
  if (baseHash.startsWith("Proyecto")) {
    const id = baseHash.replace("Proyecto", "");
    return `projectview.php?id=${id}`;
  }

  if (baseHash.startsWith("Bodega")) {
    const id = baseHash.replace("Bodega", "");
    return `warehouseview.php?id=${id}`;
  }

  if (baseHash.startsWith("Transferencia::proyecto")) {
    const id = baseHash.split("::")[2];
    return `transfer_project.php?id=${id}`;
  }

  if (baseHash.startsWith("Transferencia::bodega")) {
    const id = baseHash.split("::")[2];
    return `transfer_warehouse.php?id=${id}`;
  }

  switch (baseHash) {
    case "Dashboard":
      return "homeview.php";
    case "Inventario":
      return "inventoryview.php";
    case "Proveedores":
      return "suppliersview.php";
    case "Administrador":
      return "administrator_view.php";
    case "Categorías":
      return "categoriesview.php";
    case "Subcategorías":
      return "subcategorie_view.php";
    case "Movimientos":
      return "movementsview.php";
    case "Reportes":
      return "reportsview.php";
    case "Novedades":
      return "newsview.php";
    default:
      return "homeview.php";
  }
}

function resolveViewFromHash() {
  const view = getViewFromHash();
  handleLoadView(view);
}

window.addEventListener("DOMContentLoaded", resolveViewFromHash);
window.addEventListener("hashchange", resolveViewFromHash);
