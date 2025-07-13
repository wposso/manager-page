<?php
// Verifica si el usuario es administrador
function isAdmin()
{
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'ADMIN';
}

// Verifica si el usuario es supervisor
function isSupervisor()
{
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'SUPERVISOR';
}

// Verifica si el usuario es operador
function isOperador()
{
    return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'OPERADOR';
}

// Verifica si el usuario puede editar (ADMIN o SUPERVISOR)
function canEdit()
{
    return isAdmin() || isSupervisor();
}

// Verifica si el usuario puede acceder al panel de administración (solo ADMIN)
function canAccessAdminPanel()
{
    return isAdmin();
}

// Devuelve clase CSS y atributos HTML para desactivar elementos si es operador
function getDisabledState()
{
    if (isOperador()) {
        return [
            'class' => 'btn-disabled',
            'attr' => 'disabled'
        ];
    }
    return [
        'class' => '',
        'attr' => ''
    ];
}
?>