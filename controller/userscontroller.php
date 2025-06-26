<?php
require_once __DIR__ . "/../functions/getusers.php";

function getUsers()
{
    $response = getAllUsersFromDb();

    // if (!$response) {
    //     return die("Error al obtener la lista de usuarios");
    // }

    return $response;
}
?>