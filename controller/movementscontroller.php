<?php require_once __DIR__ . "/../functions/getmovements.php";
function handlemovements()
{
    $fetch = getAllMovementFromDb();
    if ($fetch == null) {
        return die("No hay datos disponibles");
    }
    return $fetch;
}
?>