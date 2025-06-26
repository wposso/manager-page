<?php
require_once __DIR__ . "/../functions/getnews.php";

function handlegetnews()
{
    $fetch = getAllNewsFromDb();
    return $fetch;
}
?>