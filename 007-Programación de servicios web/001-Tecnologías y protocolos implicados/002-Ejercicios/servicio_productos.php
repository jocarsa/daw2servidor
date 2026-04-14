<?php
header("Content-Type: application/json; charset=utf-8");

require_once "conexion_mysql.php";

try {
    $pdo = obtenerPDO();

    $sql = "SELECT id_producto, nombre, precio FROM producto ORDER BY nombre ASC";
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "ok" => true,
        "productos" => $productos
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al obtener productos",
        "detalle" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>