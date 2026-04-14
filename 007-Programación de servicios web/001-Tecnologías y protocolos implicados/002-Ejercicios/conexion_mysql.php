<?php

function obtenerPDO() {
    $host = "127.0.0.1";
    $port = "3307";
    $dbname = "tienda_servicios";
    $user = "root";
    $pass = "";

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
?>