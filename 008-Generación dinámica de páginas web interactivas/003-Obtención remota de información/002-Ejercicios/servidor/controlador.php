<?php

header("Content-Type: text/html; charset=utf-8");

$categoria = $_GET["categoria"] ?? "";

$categoriasPermitidas = [
    "",
    "Rol",
    "Carreras",
    "Aventura",
    "Construcción"
];

if (!in_array($categoria, $categoriasPermitidas, true)) {
    $categoria = "";
}

$urlProveedor = construirUrlProveedor($categoria);

$respuestaRemota = obtenerContenidoRemoto($urlProveedor);

if ($respuestaRemota["error"] !== "") {
    mostrarError("No se pudo conectar con el proveedor remoto.");
    exit;
}

$ofertas = json_decode($respuestaRemota["contenido"], true);

if (!is_array($ofertas)) {
    mostrarError("El proveedor remoto no ha devuelto un JSON válido.");
    exit;
}

if (count($ofertas) === 0) {
    echo '<p class="mensaje">No hay ofertas para la categoría seleccionada.</p>';
    exit;
}

mostrarOfertas($ofertas);


// --------------------------------------------------------------------
// Construye la URL absoluta del proveedor remoto simulado
// --------------------------------------------------------------------

function construirUrlProveedor($categoria) {
    $protocolo = "http";

    if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
        $protocolo = "https";
    }

    $host = $_SERVER["HTTP_HOST"];

    $carpetaProyecto = dirname(dirname($_SERVER["SCRIPT_NAME"]));

    // Codificamos cada parte de la ruta para evitar problemas con espacios, tildes, etc.
    $partesRuta = explode("/", trim($carpetaProyecto, "/"));
    $rutaCodificada = "";

    foreach ($partesRuta as $parte) {
        $rutaCodificada .= "/" . rawurlencode($parte);
    }

    $url = $protocolo . "://" . $host . $rutaCodificada . "/proveedorExterno/ofertas.php";

    if ($categoria !== "") {
        $url .= "?categoria=" . urlencode($categoria);
    }

    return $url;
}


// --------------------------------------------------------------------
// Obtiene contenido remoto con cURL
// --------------------------------------------------------------------
function obtenerContenidoRemoto($url) {
    if (!function_exists("curl_init")) {
        return [
            "contenido" => "",
            "error" => "La extensión cURL no está activada en PHP."
        ];
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Tiempo máximo para conectar.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    // Tiempo máximo total de la petición.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $contenido = curl_exec($ch);
    $error = curl_error($ch);
    $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($contenido === false || $error !== "") {
        return [
            "contenido" => "",
            "error" => $error
        ];
    }

    if ($codigoHttp < 200 || $codigoHttp >= 300) {
        return [
            "contenido" => "",
            "error" => "Código HTTP no válido: " . $codigoHttp
        ];
    }

    return [
        "contenido" => $contenido,
        "error" => ""
    ];
}


// --------------------------------------------------------------------
// Genera el HTML que recibirá el cliente
// --------------------------------------------------------------------
function mostrarOfertas($ofertas) {
    echo '<div class="ofertas">';

    foreach ($ofertas as $oferta) {
        echo '<article class="oferta">';
        echo '<h3>' . htmlspecialchars($oferta["titulo"]) . '</h3>';
        echo '<p>Categoría: ' . htmlspecialchars($oferta["categoria"]) . '</p>';
        echo '<p class="descuento">Descuento: ' . htmlspecialchars($oferta["descuento"]) . '%</p>';
        echo '<p>' . htmlspecialchars($oferta["descripcion"]) . '</p>';
        echo '</article>';
    }

    echo '</div>';
}


// --------------------------------------------------------------------
// Muestra errores en formato HTML
// --------------------------------------------------------------------
function mostrarError($mensaje) {
    echo '<p class="error">' . htmlspecialchars($mensaje) . '</p>';
}