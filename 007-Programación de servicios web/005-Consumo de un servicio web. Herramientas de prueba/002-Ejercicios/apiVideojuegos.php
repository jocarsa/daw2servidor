<?php
header("Content-Type: application/json; charset=utf-8");

// ----------------------------------------------------
// Conexión a MySQL
// ----------------------------------------------------
function obtenerPDO() {
    $host = "127.0.0.1";
    $port = "3307";
    $dbname = "videojuegos_asir";
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

// ----------------------------------------------------
// Responder siempre en JSON
// ----------------------------------------------------
function responder($codigo, $datos = null) {
    http_response_code($codigo);

    if ($datos !== null) {
        echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    exit;
}

// ----------------------------------------------------
// Leer JSON enviado en el cuerpo de la petición
// ----------------------------------------------------
function leerJSONBody() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if ($raw !== "" && $data === null) {
        // 400 Bad Request: la petición contiene un JSON incorrecto.
        responder(400, [
            "error" => "JSON inválido"
        ]);
    }

    return $data ?? [];
}

// ----------------------------------------------------
// Obtener la ruta después de api-videojuegos.php
// Ejemplos:
// api-videojuegos.php/videojuegos
// api-videojuegos.php/videojuegos/3
// ----------------------------------------------------
function obtenerRuta() {
    $pathInfo = $_SERVER["PATH_INFO"] ?? "";

    if ($pathInfo !== "") {
        return trim($pathInfo, "/");
    }
}

// ----------------------------------------------------
// Buscar un videojuego por ID
// ----------------------------------------------------
function buscarVideojuegoPorId($pdo, $id) {
    $sql = "
        SELECT 
            v.id_videojuego AS id,
            v.titulo,
            v.fecha_lanzamiento,
            v.pegi,
            v.precio_base,
            v.motor,
            v.es_multijugador,
            e.nombre AS estudio
        FROM videojuego v
        LEFT JOIN estudio e ON v.id_estudio = e.id_estudio
        WHERE v.id_videojuego = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id" => $id
    ]);

    $videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$videojuego) {
        return null;
    }

    $videojuego["id"] = (int)$videojuego["id"];
    $videojuego["pegi"] = $videojuego["pegi"] !== null ? (int)$videojuego["pegi"] : null;
    $videojuego["precio_base"] = $videojuego["precio_base"] !== null ? (float)$videojuego["precio_base"] : null;
    $videojuego["es_multijugador"] = (bool)$videojuego["es_multijugador"];

    return $videojuego;
}

// ----------------------------------------------------
// Inicio del procesamiento REST
// ----------------------------------------------------
try {
    $pdo = obtenerPDO();

    $metodo = $_SERVER["REQUEST_METHOD"];

    $ruta = obtenerRuta();
    $partesRuta = explode("/", trim($ruta, "/"));

    $recurso = $partesRuta[0] ?? "";
    $id = $partesRuta[1] ?? null;

    if ($recurso !== "videojuegos") {
        // 404 Not Found: el recurso solicitado no existe.
        responder(404, [
            "error" => "Recurso no encontrado. Usa /videojuegos o /videojuegos/{id}"
        ]);
    }

    // ----------------------------------------------------
    // GET /videojuegos
    // Devuelve todos los videojuegos.
    // Permite filtros opcionales:
    // ?precioMax=60
    // ?pegiMax=16
    // ?multijugador=true
    // ----------------------------------------------------
    if ($metodo === "GET" && $id === null) {
        $sql = "
            SELECT 
                v.id_videojuego AS id,
                v.titulo,
                v.fecha_lanzamiento,
                v.pegi,
                v.precio_base,
                v.motor,
                v.es_multijugador,
                e.nombre AS estudio
            FROM videojuego v
            LEFT JOIN estudio e ON v.id_estudio = e.id_estudio
            WHERE 1 = 1
        ";

        $params = [];

        if (isset($_GET["precioMax"]) && $_GET["precioMax"] !== "") {
            if (!is_numeric($_GET["precioMax"])) {
                // 400 Bad Request: el filtro enviado no tiene un formato válido.
                responder(400, [
                    "error" => "precioMax debe ser numérico"
                ]);
            }

            $sql .= " AND v.precio_base IS NOT NULL AND v.precio_base <= :precioMax";
            $params[":precioMax"] = (float)$_GET["precioMax"];
        }

        if (isset($_GET["pegiMax"]) && $_GET["pegiMax"] !== "") {
            if (!is_numeric($_GET["pegiMax"])) {
                // 400 Bad Request: el filtro enviado no tiene un formato válido.
                responder(400, [
                    "error" => "pegiMax debe ser numérico"
                ]);
            }

            $sql .= " AND v.pegi IS NOT NULL AND v.pegi <= :pegiMax";
            $params[":pegiMax"] = (int)$_GET["pegiMax"];
        }

        if (isset($_GET["multijugador"]) && $_GET["multijugador"] !== "") {
            $valor = $_GET["multijugador"];
            $multijugador = ($valor === "true" || $valor === "1") ? 1 : 0;

            $sql .= " AND v.es_multijugador = :multijugador";
            $params[":multijugador"] = $multijugador;
        }

        $sql .= " ORDER BY v.id_videojuego ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($videojuegos as &$juego) {
            $juego["id"] = (int)$juego["id"];
            $juego["pegi"] = $juego["pegi"] !== null ? (int)$juego["pegi"] : null;
            $juego["precio_base"] = $juego["precio_base"] !== null ? (float)$juego["precio_base"] : null;
            $juego["es_multijugador"] = (bool)$juego["es_multijugador"];
        }

        // 200 OK: la petición se ha procesado correctamente.
        responder(200, [
            "total" => count($videojuegos),
            "videojuegos" => $videojuegos
        ]);
    }

    // ----------------------------------------------------
    // GET /videojuegos/{id}
    // Devuelve un videojuego concreto
    // ----------------------------------------------------
    if ($metodo === "GET" && $id !== null) {
        if (!is_numeric($id)) {
            // 400 Bad Request: el ID enviado no es válido.
            responder(400, [
                "error" => "El ID debe ser numérico"
            ]);
        }

        $videojuego = buscarVideojuegoPorId($pdo, (int)$id);

        if (!$videojuego) {
            // 404 Not Found: no existe ningún videojuego con ese ID.
            responder(404, [
                "error" => "Videojuego no encontrado"
            ]);
        }

        // 200 OK: la petición se ha procesado correctamente.
        responder(200, $videojuego);
    }

    // ----------------------------------------------------
    // POST /videojuegos
    // Crea un videojuego nuevo
    // ----------------------------------------------------
    if ($metodo === "POST" && $id === null) {
        $data = leerJSONBody();

        $titulo = trim($data["titulo"] ?? "");
        $fechaLanzamiento = trim($data["fecha_lanzamiento"] ?? "");
        $pegi = $data["pegi"] ?? null;
        $precioBase = $data["precio_base"] ?? null;
        $motor = trim($data["motor"] ?? "");
        $esMultijugador = $data["es_multijugador"] ?? false;

        if ($titulo === "") {
            // 400 Bad Request: faltan datos obligatorios.
            responder(400, [
                "error" => "El campo titulo es obligatorio"
            ]);
        }

        if ($pegi !== null && $pegi !== "" && !is_numeric($pegi)) {
            // 400 Bad Request: el dato enviado no tiene el formato esperado.
            responder(400, [
                "error" => "El campo pegi debe ser numérico"
            ]);
        }

        if ($precioBase !== null && $precioBase !== "" && !is_numeric($precioBase)) {
            // 400 Bad Request: el dato enviado no tiene el formato esperado.
            responder(400, [
                "error" => "El campo precio_base debe ser numérico"
            ]);
        }

        $sql = "
            INSERT INTO videojuego 
            (titulo, fecha_lanzamiento, pegi, precio_base, motor, es_multijugador)
            VALUES 
            (:titulo, :fecha_lanzamiento, :pegi, :precio_base, :motor, :es_multijugador)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":titulo" => $titulo,
            ":fecha_lanzamiento" => $fechaLanzamiento !== "" ? $fechaLanzamiento : null,
            ":pegi" => ($pegi !== null && $pegi !== "") ? (int)$pegi : null,
            ":precio_base" => ($precioBase !== null && $precioBase !== "") ? (float)$precioBase : null,
            ":motor" => $motor !== "" ? $motor : null,
            ":es_multijugador" => $esMultijugador ? 1 : 0
        ]);

        $nuevoId = (int)$pdo->lastInsertId();
        $videojuegoCreado = buscarVideojuegoPorId($pdo, $nuevoId);

        // 201 Created: el recurso se ha creado correctamente.
        responder(201, $videojuegoCreado);
    }

    // ----------------------------------------------------
    // PATCH /videojuegos/{id}
    // Actualiza parcialmente un videojuego
    // ----------------------------------------------------
    if ($metodo === "PATCH" && $id !== null) {
        if (!is_numeric($id)) {
            // 400 Bad Request: el ID enviado no es válido.
            responder(400, [
                "error" => "El ID debe ser numérico"
            ]);
        }

        $videojuegoActual = buscarVideojuegoPorId($pdo, (int)$id);

        if (!$videojuegoActual) {
            // 404 Not Found: no existe ningún videojuego con ese ID.
            responder(404, [
                "error" => "Videojuego no encontrado"
            ]);
        }

        $data = leerJSONBody();

        if (empty($data)) {
            // 400 Bad Request: no se ha enviado ningún campo para actualizar.
            responder(400, [
                "error" => "Debes enviar al menos un campo para actualizar"
            ]);
        }

        $campos = [];
        $params = [
            ":id" => (int)$id
        ];

        if (isset($data["titulo"])) {
            $titulo = trim($data["titulo"]);

            if ($titulo === "") {
                // 400 Bad Request: el campo titulo no puede quedar vacío.
                responder(400, [
                    "error" => "El campo titulo no puede estar vacío"
                ]);
            }

            $campos[] = "titulo = :titulo";
            $params[":titulo"] = $titulo;
        }

        if (isset($data["fecha_lanzamiento"])) {
            $fechaLanzamiento = trim($data["fecha_lanzamiento"]);
            $campos[] = "fecha_lanzamiento = :fecha_lanzamiento";
            $params[":fecha_lanzamiento"] = $fechaLanzamiento !== "" ? $fechaLanzamiento : null;
        }

        if (isset($data["pegi"])) {
            if ($data["pegi"] !== null && $data["pegi"] !== "" && !is_numeric($data["pegi"])) {
                // 400 Bad Request: el campo pegi debe ser numérico.
                responder(400, [
                    "error" => "El campo pegi debe ser numérico"
                ]);
            }

            $campos[] = "pegi = :pegi";
            $params[":pegi"] = ($data["pegi"] !== null && $data["pegi"] !== "") ? (int)$data["pegi"] : null;
        }

        if (isset($data["precio_base"])) {
            if ($data["precio_base"] !== null && $data["precio_base"] !== "" && !is_numeric($data["precio_base"])) {
                // 400 Bad Request: el campo precio_base debe ser numérico.
                responder(400, [
                    "error" => "El campo precio_base debe ser numérico"
                ]);
            }

            $campos[] = "precio_base = :precio_base";
            $params[":precio_base"] = ($data["precio_base"] !== null && $data["precio_base"] !== "") ? (float)$data["precio_base"] : null;
        }

        if (isset($data["motor"])) {
            $motor = trim($data["motor"]);
            $campos[] = "motor = :motor";
            $params[":motor"] = $motor !== "" ? $motor : null;
        }

        if (isset($data["es_multijugador"])) {
            $campos[] = "es_multijugador = :es_multijugador";
            $params[":es_multijugador"] = $data["es_multijugador"] ? 1 : 0;
        }

        if (empty($campos)) {
            // 400 Bad Request: los campos enviados no son modificables o no son válidos.
            responder(400, [
                "error" => "No se ha enviado ningún campo válido para actualizar"
            ]);
        }

        $sql = "
            UPDATE videojuego
            SET " . implode(", ", $campos) . "
            WHERE id_videojuego = :id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $videojuegoActualizado = buscarVideojuegoPorId($pdo, (int)$id);

        // 200 OK: el recurso se ha actualizado correctamente.
        responder(200, $videojuegoActualizado);
    }

    // ----------------------------------------------------
    // DELETE /videojuegos/{id}
    // Elimina un videojuego
    // ----------------------------------------------------
    if ($metodo === "DELETE" && $id !== null) {
        if (!is_numeric($id)) {
            // 400 Bad Request: el ID enviado no es válido.
            responder(400, [
                "error" => "El ID debe ser numérico"
            ]);
        }

        $videojuegoActual = buscarVideojuegoPorId($pdo, (int)$id);

        if (!$videojuegoActual) {
            // 404 Not Found: no existe ningún videojuego con ese ID.
            responder(404, [
                "error" => "Videojuego no encontrado"
            ]);
        }

        try {
            $stmt = $pdo->prepare("
                DELETE FROM videojuego
                WHERE id_videojuego = :id
            ");

            $stmt->execute([
                ":id" => (int)$id
            ]);

            // 204 No Content: la operación se ha realizado correctamente, pero no se devuelve cuerpo.
            responder(204);

        } catch (PDOException $e) {
            // 409 Conflict: no se puede completar la operación por un conflicto con el estado actual del recurso.
            responder(409, [
                "error" => "No se puede eliminar el videojuego porque está relacionado con otros datos",
                "detalle" => $e->getMessage()
            ]);
        }
    }

    // 405 Method Not Allowed: el método HTTP no está permitido para esta ruta.
    responder(405, [
        "error" => "Método no permitido para esta ruta"
    ]);

} catch (PDOException $e) {
    // 500 Internal Server Error: error interno del servidor.
    responder(500, [
        "error" => "Error interno del servidor",
        "detalle" => $e->getMessage()
    ]);
}
?>