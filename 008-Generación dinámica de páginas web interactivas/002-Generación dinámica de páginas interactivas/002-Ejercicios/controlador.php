<?php

header("Content-Type: application/json; charset=utf-8");

// Mapa de servicios permitidos
$servicios = [
    "videojuegos" => [
        "archivo" => "servicios/servicio_videojuegos.php",
        "funcion" => "servicioVideojuegos"
    ],
    "alumnos" => [
        "archivo" => "servicios/servicio_alumnos.php",
        "funcion" => "servicioAlumnos"
    ]
];

// Método HTTP usado
$metodo = $_SERVER["REQUEST_METHOD"];

// Ruta solicitada
// Ejemplo: controlador.php/videojuegos
$ruta = $_SERVER["PATH_INFO"] ?? "";
$ruta = trim($ruta, "/");

// Si no se ha indicado ningún servicio
if ($ruta === "") {
    responderJson([
        "error" => "No se ha indicado ningún servicio",
        "ejemplos" => [
            "GET controlador.php/videojuegos",
            "POST controlador.php/videojuegos",
            "GET controlador.php/alumnos",
            "POST controlador.php/alumnos"
        ]
    ], 400);
}

// Comprobamos si el servicio existe
if (!isset($servicios[$ruta])) {
    responderJson([
        "error" => "Servicio no encontrado"
    ], 404);
}

// Ahora que sabemos que el servicio es válido,
// cargamos solamente el archivo necesario
$archivoServicio = $servicios[$ruta]["archivo"];
$funcionServicio = $servicios[$ruta]["funcion"];

require_once $archivoServicio;

// Leemos los datos enviados en el cuerpo de la petición
$entrada = file_get_contents("php://input");
$datosEntrada = json_decode($entrada, true);

// Si no se ha enviado JSON, usamos un array vacío
if ($datosEntrada === null) {
    $datosEntrada = [];
}

// Delegamos la petición al servicio correspondiente
$resultado = $funcionServicio($metodo, $datosEntrada);

// Devolvemos la respuesta
responderJson($resultado["datos"], $resultado["codigo"]);


function responderJson($datos, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}