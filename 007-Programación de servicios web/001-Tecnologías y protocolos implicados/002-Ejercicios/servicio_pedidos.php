<?php
header("Content-Type: application/json; charset=utf-8");

require_once "conexion_mysql.php";

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$cliente = trim($data["cliente"] ?? "");
$idProducto = $data["id_producto"] ?? null;
$cantidad = $data["cantidad"] ?? null;

if ($cliente === "" || !$idProducto || !$cantidad) {
    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Debes enviar cliente, id_producto y cantidad"
    ], JSON_PRETTY_PRINT);
    exit;
}

if (!is_numeric($idProducto) || !is_numeric($cantidad) || (int)$cantidad < 1) {
    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Los datos numéricos no son válidos"
    ], JSON_PRETTY_PRINT);
    exit;
}

try {
    $pdo = obtenerPDO();

    $pdo->beginTransaction();

    $sqlProducto = "
        SELECT p.id_producto, p.nombre, p.precio, s.unidades
        FROM producto p
        INNER JOIN stock s ON p.id_producto = s.id_producto
        WHERE p.id_producto = :id_producto
        FOR UPDATE
    ";

    $stmtProducto = $pdo->prepare($sqlProducto);
    $stmtProducto->execute([
        ":id_producto" => (int)$idProducto
    ]);

    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        $pdo->rollBack();

        http_response_code(404);
        echo json_encode([
            "ok" => false,
            "mensaje" => "Producto no encontrado"
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $stockActual = (int)$producto["unidades"];
    $cantidad = (int)$cantidad;

    if ($cantidad > $stockActual) {
        $pdo->rollBack();

        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "mensaje" => "No hay stock suficiente",
            "stock_actual" => $stockActual
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $precioUnitario = (float)$producto["precio"];
    $total = $precioUnitario * $cantidad;

    $sqlInsert = "
        INSERT INTO pedido (cliente, id_producto, cantidad, precio_unitario, total)
        VALUES (:cliente, :id_producto, :cantidad, :precio_unitario, :total)
    ";

    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ":cliente" => $cliente,
        ":id_producto" => (int)$idProducto,
        ":cantidad" => $cantidad,
        ":precio_unitario" => $precioUnitario,
        ":total" => $total
    ]);

    $sqlUpdateStock = "
        UPDATE stock
        SET unidades = unidades - :cantidad
        WHERE id_producto = :id_producto
    ";

    $stmtUpdateStock = $pdo->prepare($sqlUpdateStock);
    $stmtUpdateStock->execute([
        ":cantidad" => $cantidad,
        ":id_producto" => (int)$idProducto
    ]);

    $pdo->commit();

    echo json_encode([
        "ok" => true,
        "mensaje" => "Pedido realizado correctamente",
        "id_pedido" => $pdo->lastInsertId(),
        "producto" => $producto["nombre"],
        "cliente" => $cliente,
        "cantidad" => $cantidad,
        "precio_unitario" => $precioUnitario,
        "total" => $total,
        "stock_restante" => $stockActual - $cantidad
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al crear pedido",
        "detalle" => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>