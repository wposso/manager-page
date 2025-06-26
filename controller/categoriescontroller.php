<?php
require_once __DIR__ . "/../functions/getcategories.php";

function handlecategories()
{
    $fetch = getAllCategoriesFromDb();
    if (!$fetch) {
        return die("Error al extraer los registros");
    }

    return $fetch;
}
?>