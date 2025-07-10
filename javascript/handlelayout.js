function handleLoadView(x) {
  const layout = document.getElementById("layout");
  const loader = document.getElementById("loader");

  loader.style.display = "flex";

  setTimeout(() => {
    fetch("views/" + x)
      .then((r) => r.text())
      .then((h) => {
        layout.innerHTML = h;

        if (
          x === "homeview.php" &&
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
        }, 300);
      });
  }, 150);
}

function getCleanHash() {
  const rawHash = window.location.hash || "#Dashboard";
  const [hashOnly] = rawHash.split("::");
  return decodeURIComponent(hashOnly);
}

function resolveViewFromHash() {
  const hash = getCleanHash();

  switch (hash) {
    case "#Proveedores":
      handleLoadView("suppliersview.php");
      break;
    case "#Administrador":
      handleLoadView("administrator_view.php");
      break;

    case "#Categorías":
      handleLoadView("categoriesview.php");
      break;
    case "#Subcategorías":
      handleLoadView("subcategorie_view.php");
      break;
    case "#Inventario":
      handleLoadView("inventoryview.php");
      break;
    case "#Movimientos":
      handleLoadView("movementsview.php");
      break;
    case "#Reportes":
      handleLoadView("reportsview.php");
      break;
    case "#Novedades":
      handleLoadView("newsview.php");
      break;
    case "#Dashboard":
    default:
      handleLoadView("homeview.php");
      break;
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const hash = window.location.hash;

  // Verifica si hay mensaje
  if (hash.includes("::")) {
    const [viewHash, msg] = hash.split("::");

    // Muestra el mensaje
    if (msg) alert(decodeURIComponent(msg));

    // Reemplaza el hash solo con la vista limpia
    window.location.hash = viewHash;
  }

  resolveViewFromHash();
});

window.addEventListener("hashchange", resolveViewFromHash);
