<?php
require_once __DIR__ . "/../functions/getsuppliers.php";

function handlesuppliers()
{
    $fetch = getAllSuppliersFromDb();

    return $fetch;
}
?>