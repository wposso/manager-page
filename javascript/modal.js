// Función que abre una modal
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "flex";
}

// Función que cierra una modal
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "none";
}

// Alterna el estado del dropdown
function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    if (menu) {
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }
}

// Cierra dropdown al hacer clic fuera
document.addEventListener("click", function (event) {
    const dropdown = document.querySelector(".n_dropdown");
    const menu = document.getElementById("dropdownMenu");
    if (dropdown && menu && !dropdown.contains(event.target)) {
        menu.style.display = "none";
    }
});

// Cierra modal si se hace clic fuera
window.onclick = function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
};

// Esta función se debe ejecutar luego de cargar cada vista dinámicamente
function initModalEventListeners() {
    console.log("Reasignando eventos de modal y dropdown...");

    // Botón para abrir dropdown
    const dropdownBtn = document.querySelector('.n_dropdown input[type="button"]');
    if (dropdownBtn) dropdownBtn.onclick = toggleDropdown;

    // Botones dentro del dropdown
    const registerBtn = document.querySelector("#dropdownMenu button:nth-child(1)");
    const deleteBtn = document.querySelector("#dropdownMenu button:nth-child(2)");

    if (registerBtn) registerBtn.onclick = () => openModal("registerModal");
    if (deleteBtn) deleteBtn.onclick = () => openModal("deleteModal");
}
