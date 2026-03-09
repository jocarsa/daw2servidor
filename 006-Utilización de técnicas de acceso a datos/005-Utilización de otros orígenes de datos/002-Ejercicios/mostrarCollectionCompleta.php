<?php

require 'vendor/autoload.php';

use MongoDB\Client;

// Conectar con MongoDB
$client = new Client("mongodb://localhost:27017");

// Seleccionar base de datos
$db = $client->Tienda;

// Seleccionar colección
$collection = $db->Pedidos;

// Obtener pedidos
$cursor = $collection->find();

foreach ($cursor as $pedido) {

    if (isset($pedido["numero_pedido"])) {
        echo "Pedido: " . $pedido["numero_pedido"] . "<br>";
    }

    if (isset($pedido["fecha"])) {
        echo "Fecha: " . $pedido["fecha"] . "<br>";
    }

    if (isset($pedido["estado"])) {
        echo "Estado: " . $pedido["estado"] . "<br>";
    }

    echo "<hr>";

    // Cliente
    if (isset($pedido["cliente"])) {

        echo "<strong>Cliente</strong><br>";

        $cliente = $pedido["cliente"];

        if (isset($cliente["nombre"])) {
            echo "Nombre: " . $cliente["nombre"] . "<br>";
        }

        if (isset($cliente["correo"])) {
            echo "Correo: " . $cliente["correo"] . "<br>";
        }

        if (isset($cliente["telefono"])) {
            echo "Teléfono: " . $cliente["telefono"] . "<br>";
        }
    }

    echo "<hr>";

    // Productos (array de objetos)
    if (isset($pedido["productos"]) && is_iterable($pedido["productos"])) {

        echo "<strong>Productos</strong><br>";

        foreach ($pedido["productos"] as $producto) {

            if (isset($producto["nombre"])) {
                echo "Producto: " . $producto["nombre"] . "<br>";
            }

            if (isset($producto["precio"])) {
                echo "Precio: " . $producto["precio"] . "<br>";
            }

            if (isset($producto["cantidad"])) {
                echo "Cantidad: " . $producto["cantidad"] . "<br>";
            }

            echo "<br>";
        }
    }

    echo "<hr>";

    if (isset($pedido["total"])) {
            echo "Total: " . $pedido["total"] . "<br>";
    }

    echo "<hr><hr>";
}