<?php

function servicioVideojuegos($metodo, $datosEntrada) {
    $archivo = "datos/videojuegos.json";

    if ($metodo === "GET") {
        return consultarVideojuegos($archivo);
    }

    if ($metodo === "POST") {
        return añadirVideojuego($archivo, $datosEntrada);
    }

    return [
        "codigo" => 405,
        "datos" => [
            "error" => "Método no permitido para videojuegos"
        ]
    ];
}


function consultarVideojuegos($archivo) {
    $videojuegos = leerJson($archivo);

    return [
        "codigo" => 200,
        "datos" => $videojuegos
    ];
}


function añadirVideojuego($archivo, $datosEntrada) {
    if (!isset($datosEntrada["titulo"]) || trim($datosEntrada["titulo"]) === "") {
        return [
            "codigo" => 400,
            "datos" => [
                "error" => "El título del videojuego es obligatorio"
            ]
        ];
    }

    if (!isset($datosEntrada["genero"]) || trim($datosEntrada["genero"]) === "") {
        return [
            "codigo" => 400,
            "datos" => [
                "error" => "El género del videojuego es obligatorio"
            ]
        ];
    }

    $videojuegos = leerJson($archivo);

    $nuevoVideojuego = [
        "id" => generarNuevoId($videojuegos),
        "titulo" => $datosEntrada["titulo"],
        "genero" => $datosEntrada["genero"]
    ];

    $videojuegos[] = $nuevoVideojuego;

    guardarJson($archivo, $videojuegos);

    return [
        "codigo" => 201,
        "datos" => [
            "mensaje" => "Videojuego añadido correctamente",
            "videojuego" => $nuevoVideojuego
        ]
    ];
}


function leerJson($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }

    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);

    if ($datos === null) {
        return [];
    }

    return $datos;
}


function guardarJson($archivo, $datos) {
    file_put_contents(
        $archivo,
        json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}


function generarNuevoId($datos) {
    $mayorId = 0;

    foreach ($datos as $elemento) {
        if ($elemento["id"] > $mayorId) {
            $mayorId = $elemento["id"];
        }
    }

    return $mayorId + 1;
}