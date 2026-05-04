<?php

header("Content-Type: text/html; charset=utf-8");

$archivoJson = __DIR__ . "/videojuegos.json";

$videojuegos = leerVideojuegosDesdeJson($archivoJson);

$genero = $_GET["genero"] ?? "";
$orden = $_GET["orden"] ?? "titulo";
$vista = $_GET["vista"] ?? "tarjetas";

$generosPermitidos = [
    "",
    "Aventura",
    "Carreras",
    "Construcción",
    "Rol",
    "Lucha"
];

$ordenesPermitidos = [
    "titulo",
    "precio_asc",
    "precio_desc"
];

$vistasPermitidas = [
    "tarjetas",
    "tabla"
];

if (!in_array($genero, $generosPermitidos, true)) {
    $genero = "";
}

if (!in_array($orden, $ordenesPermitidos, true)) {
    $orden = "titulo";
}

if (!in_array($vista, $vistasPermitidas, true)) {
    $vista = "tarjetas";
}

$resultado = filtrarVideojuegos($videojuegos, $genero);
ordenarVideojuegos($resultado, $orden);

if (count($resultado) === 0) {
    echo '<p class="mensaje">No hay videojuegos que cumplan los filtros seleccionados.</p>';
    exit;
}

if ($vista === "tabla") {
    mostrarTabla($resultado);
} else {
    mostrarTarjetas($resultado);
}


function leerVideojuegosDesdeJson($archivoJson) {
    if (!file_exists($archivoJson)) {
        return [];
    }

    $contenido = file_get_contents($archivoJson);

    if ($contenido === false || trim($contenido) === "") {
        return [];
    }

    $datos = json_decode($contenido, true);

    if (!is_array($datos)) {
        return [];
    }

    return $datos;
}


function filtrarVideojuegos($videojuegos, $genero) {
    $resultado = [];

    foreach ($videojuegos as $videojuego) {
        if ($genero === "" || $videojuego["genero"] === $genero) {
            $resultado[] = $videojuego;
        }
    }

    return $resultado;
}


function ordenarVideojuegos(&$videojuegos, $orden) {
    usort($videojuegos, function ($a, $b) use ($orden) {
        if ($orden === "precio_asc") {
            return $a["precio"] <=> $b["precio"];
        }

        if ($orden === "precio_desc") {
            return $b["precio"] <=> $a["precio"];
        }

        return strcmp($a["titulo"], $b["titulo"]);
    });
}


function mostrarTarjetas($videojuegos) {
    echo '<div class="catalogo-tarjetas">';

    foreach ($videojuegos as $videojuego) {
        echo '<article class="tarjeta">';
        echo '<h3>' . htmlspecialchars($videojuego["titulo"]) . '</h3>';
        echo '<p>Género: ' . htmlspecialchars($videojuego["genero"]) . '</p>';
        echo '<p>PEGI: ' . htmlspecialchars($videojuego["pegi"]) . '</p>';
        echo '<p>Multijugador: ' . ($videojuego["multijugador"] ? "Sí" : "No") . '</p>';
        echo '<p class="precio">Precio: ' . formatearPrecio($videojuego["precio"]) . '</p>';
        echo '</article>';
    }

    echo '</div>';
}


function mostrarTabla($videojuegos) {
    echo '<table class="tabla-catalogo">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Título</th>';
    echo '<th>Género</th>';
    echo '<th>PEGI</th>';
    echo '<th>Multijugador</th>';
    echo '<th>Precio</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($videojuegos as $videojuego) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($videojuego["titulo"]) . '</td>';
        echo '<td>' . htmlspecialchars($videojuego["genero"]) . '</td>';
        echo '<td>' . htmlspecialchars($videojuego["pegi"]) . '</td>';
        echo '<td>' . ($videojuego["multijugador"] ? "Sí" : "No") . '</td>';
        echo '<td>' . formatearPrecio($videojuego["precio"]) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}


function formatearPrecio($precio) {
    return number_format($precio, 2, ",", ".") . " €";
}