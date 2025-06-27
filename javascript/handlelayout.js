function handleLoadView(x) {
  fetch("views/" + x)
    .then((r) => r.text())
    .then((h) => {
      const layout = document.getElementById("layout");
      layout.innerHTML = h;

      if (x === "homeview.php" && typeof renderDashboardCharts === "function") {
        setTimeout(renderDashboardCharts, 50);
      }

      if (typeof initModalEventListeners === "function") {
        initModalEventListeners();
      }
    });
}

window.onload = () => handleLoadView("homeview.php");
