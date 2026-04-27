<?php
header("Content-Type: application/json; charset=utf-8");

$archivoDatos = __DIR__ . "/libros.json";

// ----------------------------------------------------
// Crear datos iniciales si no existe el fichero JSON
// ----------------------------------------------------
if (!file_exists($archivoDatos)) {
    $librosIniciales = [
        [
            "id" => 1,
            "titulo" => "El Quijote",
            "autor" => "Miguel de Cervantes",
            "disponible" => true
        ],
        [
            "id" => 2,
            "titulo" => "La sombra del viento",
            "autor" => "Carlos Ruiz Zafón",
            "disponible" => false
        ],
        [
            "id" => 3,
            "titulo" => "Cien años de soledad",
            "autor" => "Gabriel García Márquez",
            "disponible" => true
        ]
    ];

    file_put_contents(
        $archivoDatos,
        json_encode($librosIniciales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

// ----------------------------------------------------
// Funciones auxiliares
// ----------------------------------------------------
function leerLibros($archivoDatos) {
    $contenido = file_get_contents($archivoDatos);
    return json_decode($contenido, true);
}

function guardarLibros($archivoDatos, $libros) {
    file_put_contents(
        $archivoDatos,
        json_encode($libros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function responder($codigo, $datos = null) {
    http_response_code($codigo);

    if ($datos !== null) {
        echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    exit;
}

function leerJSONBody() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if ($raw !== "" && $data === null) {
        // 400 Bad Request: la petición está mal formada o contiene datos inválidos.
        responder(400, [
            "error" => "JSON inválido"
        ]);
    }

    return $data ?? [];
}

// ----------------------------------------------------
// Obtener método HTTP y ruta
// ----------------------------------------------------
$metodo = $_SERVER["REQUEST_METHOD"];

// Ejemplo de PATH_INFO:
// /libros
// /libros/1
$ruta = $_SERVER["PATH_INFO"] ?? "";

// Quitar barras sobrantes y separar la ruta
$partesRuta = explode("/", trim($ruta, "/"));

$recurso = $partesRuta[0] ?? "";
$id = $partesRuta[1] ?? null;

// Solo aceptamos el recurso libros
if ($recurso !== "libros") {
    // 404 Not Found: el recurso solicitado no existe.
    responder(404, [
        "error" => "Recurso no encontrado. Usa /libros o /libros/{id}"
    ]);
}

$libros = leerLibros($archivoDatos);

// ----------------------------------------------------
// GET /libros
// Devuelve todos los libros
// ----------------------------------------------------
if ($metodo === "GET" && $id === null) {
    // 200 OK: la petición se ha procesado correctamente.
    responder(200, [
        "total" => count($libros),
        "libros" => $libros
    ]);
}

// ----------------------------------------------------
// GET /libros/{id}
// Devuelve un libro concreto
// ----------------------------------------------------
if ($metodo === "GET" && $id !== null) {
    foreach ($libros as $libro) {
        if ($libro["id"] == $id) {
            // 200 OK: la petición se ha procesado correctamente.
            responder(200, $libro);
        }
    }

    // 404 Not Found: no existe ningún libro con ese ID.
    responder(404, [
        "error" => "Libro no encontrado"
    ]);
}

// ----------------------------------------------------
// POST /libros
// Crea un libro nuevo
// ----------------------------------------------------
if ($metodo === "POST" && $id === null) {
    $data = leerJSONBody();

    $titulo = trim($data["titulo"] ?? "");
    $autor = trim($data["autor"] ?? "");
    $disponible = $data["disponible"] ?? true;

    if ($titulo === "" || $autor === "") {
        // 400 Bad Request: faltan datos obligatorios o los datos enviados no son válidos.
        responder(400, [
            "error" => "Debes enviar titulo y autor"
        ]);
    }

    $ids = array_column($libros, "id");
    $nuevoId = empty($ids) ? 1 : max($ids) + 1;

    $nuevoLibro = [
        "id" => $nuevoId,
        "titulo" => $titulo,
        "autor" => $autor,
        "disponible" => (bool)$disponible
    ];

    $libros[] = $nuevoLibro;
    guardarLibros($archivoDatos, $libros);

    // 201 Created: el recurso se ha creado correctamente.
    responder(201, $nuevoLibro);
}

// ----------------------------------------------------
// PATCH /libros/{id}
// Actualiza parcialmente un libro
// ----------------------------------------------------
if ($metodo === "PATCH" && $id !== null) {
    $data = leerJSONBody();

    foreach ($libros as $indice => $libro) {
        if ($libro["id"] == $id) {

            if (isset($data["titulo"])) {
                $libros[$indice]["titulo"] = trim($data["titulo"]);
            }

            if (isset($data["autor"])) {
                $libros[$indice]["autor"] = trim($data["autor"]);
            }

            if (isset($data["disponible"])) {
                $libros[$indice]["disponible"] = (bool)$data["disponible"];
            }

            guardarLibros($archivoDatos, $libros);

            // 200 OK: el recurso se ha actualizado correctamente y se devuelve actualizado.
            responder(200, $libros[$indice]);
        }
    }

    // 404 Not Found: no existe ningún libro con ese ID.
    responder(404, [
        "error" => "Libro no encontrado"
    ]);
}

// ----------------------------------------------------
// DELETE /libros/{id}
// Elimina un libro
// ----------------------------------------------------
if ($metodo === "DELETE" && $id !== null) {
    foreach ($libros as $indice => $libro) {
        if ($libro["id"] == $id) {
            array_splice($libros, $indice, 1);
            guardarLibros($archivoDatos, $libros);

            // 204 No Content: la operación se ha realizado correctamente, pero no se devuelve cuerpo.
            responder(204);
        }
    }

    // 404 Not Found: no existe ningún libro con ese ID.
    responder(404, [
        "error" => "Libro no encontrado"
    ]);
}

// ----------------------------------------------------
// Si el método no encaja con ningún caso
// ----------------------------------------------------

// 405 Method Not Allowed: el método HTTP no está permitido para esta ruta.
responder(405, [
    "error" => "Método no permitido para esta ruta"
]);
?>