<?php
header("Content-Type: application/json; charset=utf-8");

require_once "conexion_mysql.php";

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$idProducto = $data["id_producto"] ?? null;

if (!$idProducto || !is_numeric($idProducto)) {
    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Debes enviar un id_producto válido"
    ], JSON_PRETTY_PRINT);
    exit;
}

try {
    $pdo = obtenerPDO();

    $sql = "
        SELECT p.id_producto, p.nombre, s.unidades
        FROM producto p
        INNER JOIN stock s ON p.id_producto = s.id_producto
        WHERE p.id_producto = :id_producto
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_producto" => (int)$idProducto
    ]);

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        http_response_code(404);

        echo json_encode([
            "ok" => false,
            "mensaje" => "Producto no encontrado"
        ], JSON_PRETTY_PRINT);
        exit;
    }

    echo json_encode([
        "ok" => true,
        "producto" => $producto["nombre"],
        "stock" => (int)$producto["unidades"]
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al consultar stock",
        "detalle" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>