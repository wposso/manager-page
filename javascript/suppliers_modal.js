function toggleDropdown() {
  const dropdown = document.getElementById("dropdownOpciones");
  dropdown.style.display =
    dropdown.style.display === "block" ? "none" : "block";
}

// Cierra el dropdown si se hace clic fuera
window.addEventListener("click", function (e) {
  const d = document.getElementById("dropdownOpciones");
  if (!e.target.matches('input[value="Opciones"]')) {
    if (d && d.style.display === "block") {
      d.style.display = "none";
    }
  }
});

// Controla las modales
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = "flex";
}
function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = "none";
}
