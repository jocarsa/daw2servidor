<?php
header("Content-Type: text/plain; charset=utf-8");

// -------------------------------------------------------------
// 1) POST x-www-form-urlencoded
// -------------------------------------------------------------
if (isset($_POST["accion"]) && $_POST["accion"] === "post_numero") {
    $numero = $_POST["numero"] ?? "No recibido";
    echo "POST recibido. Número = $numero";
    exit;
}

// -------------------------------------------------------------
// 2) Petición GET
// -------------------------------------------------------------
if (isset($_GET["accion"]) && $_GET["accion"] === "get_numero") {
    $numero = $_GET["numero"] ?? "No recibido";
    echo "GET recibido. Número = $numero";
    exit;
}

// -------------------------------------------------------------
// 3) POST con JSON → Respuesta en texto
// -------------------------------------------------------------
$rawJSON = file_get_contents("php://input");
$data = json_decode($rawJSON, true);

if (isset($data["accion"]) && $data["accion"] === "json_datos") {
    $nombre = $data["persona"]["nombre"] ?? "Sin nombre";
    $edad = $data["persona"]["edad"] ?? -1;

    $mayoria = ($edad >= 18) ? "Eres mayor de edad." : "Eres menor de edad.";

    echo "Hola $nombre, tienes $edad años. $mayoria";
    exit;
}

// -------------------------------------------------------------
// 4) POST con JSON → Respuesta en JSON (nuevo botón)
// -------------------------------------------------------------
if (isset($data["accion"]) && $data["accion"] === "json_datos_json") {

    header("Content-Type: application/json; charset=utf-8");

    $nombre = $data["persona"]["nombre"] ?? "Sin nombre";
    $edad = $data["persona"]["edad"] ?? -1;

    $mayoria = ($edad >= 18);

    // Construimos una respuesta JSON
    $respuesta = [
        "saludo" => "Hola $nombre",
        "edad"   => $edad,
        "mayor_de_edad" => $mayoria,
        "mensaje" => $mayoria
            ? "Eres mayor de edad"
            : "Eres menor de edad"
    ];

    echo json_encode($respuesta, JSON_PRETTY_PRINT);
    exit;
}

echo "No se ha reconocido la petición.";
?>
