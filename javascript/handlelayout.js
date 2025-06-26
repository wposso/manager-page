function handleLoadView(x) {
  fetch("views/" + x)
    .then((r) => r.text())
    .then((h) => {
      layout.innerHTML = h;

      if (x === "homeview.php") {
        setTimeout(renderDashboardCharts, 50);
      }
    });
}

window.onload = () => handleLoadView("homeview.php");
