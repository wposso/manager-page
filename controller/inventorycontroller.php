<?php
require_once __DIR__ . "/../database/dbconnection.php";
require_once __DIR__ . "/../functions/addinventory.php";
require_once __DIR__ . "/../functions/getinventory.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? 0;
    $unit = $_POST['unit'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $description = $_POST['description'] ?? '';

    $response = addToInventory($name, $category, $price, $unit, $stock, $description);

    echo json_encode($response);
    exit;
}
;
function getAllProducts()
{
    $response = getAllUsersFromDb();
    return $response;
}
?>