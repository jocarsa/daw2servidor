<?php
header("Content-Type: text/plain; charset=utf-8");

$accion = $_GET["accion"] ?? "";

// -------------------------------------------------------------
// ACCIÓN 1: Leer cookie que viene del cliente y responder con texto
// -------------------------------------------------------------
if ($accion === "leer_cookie") {
    if (isset($_COOKIE["usuario"])) {
        echo "El servidor recibió la cookie usuario = " . $_COOKIE["usuario"];
    } else {
        echo "No se recibió ninguna cookie llamada 'usuario'";
    }
    exit;
}

// -------------------------------------------------------------
// ACCIÓN 2: El servidor crea una cookie y se la manda al cliente
// -------------------------------------------------------------
if ($accion === "servidor_set_cookie") {
    $nombre = $_COOKIE["usuario"] ?? "desconocido";

    // Creamos el mensaje que queremos enviar "en forma de cookie"
    $mensaje = "Hola $nombre. Este mensaje viene desde una cookie creada por el servidor.";

    // IMPORTANTE: setcookie() debe ejecutarse ANTES de imprimir nada (antes de cualquier echo)
    setcookie("mensaje_servidor", $mensaje, [
        "expires"  => time() + 3600,
        "path"     => "/",
        "secure"   => false,   // pon true si usas https
        "httponly" => false,   // false para que JS pueda leerla con document.cookie/getCookie
        "samesite" => "Lax"
    ]);

    echo "Cookie 'mensaje_servidor' enviada al cliente (Set-Cookie).";
    exit;
}

echo "Acción no reconocida.";
