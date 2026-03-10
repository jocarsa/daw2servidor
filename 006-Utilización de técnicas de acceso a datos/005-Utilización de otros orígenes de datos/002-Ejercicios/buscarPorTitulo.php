<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$mensaje = "";
$documentoActualizado = null;

// Conectar con el servidor MongoDB
$client = new Client("mongodb://localhost:27017");

// Seleccionar base de datos y colección
$db = $client->Videojuegos;
$collection = $db->Juegos;

// =====================================================
// PROCESAR FORMULARIOS
// =====================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    // -------------------------------------------------
    // 1) INSERTAR
    // -------------------------------------------------
    if ($accion === "insertar") {

        $titulo = trim($_POST["titulo"] ?? "");
        $precio = $_POST["precio"] ?? "";
        $fecha_lanzamiento = trim($_POST["fecha_lanzamiento"] ?? "");
        $genero = trim($_POST["genero"] ?? "");
        $pegi = $_POST["pegi"] ?? "";
        $motor = trim($_POST["motor"] ?? "");

        $documento = [];

        if ($titulo !== "") {
            $documento["titulo"] = $titulo;
        }

        if ($precio !== "") {
            $documento["precio"] = (float)$precio;
        }

        if ($fecha_lanzamiento !== "") {
            $documento["fecha_lanzamiento"] = $fecha_lanzamiento;
        }

        if ($genero !== "") {
            $documento["genero"] = $genero;
        }

        if ($pegi !== "") {
            $documento["pegi"] = (int)$pegi;
        }

        if ($motor !== "") {
            $documento["motor"] = $motor;
        }

        if (!empty($documento)) {
            $resultado = $collection->insertOne($documento);
            $mensaje = "Documento insertado con ID: " . $resultado->getInsertedId();
        } else {
            $mensaje = "No se ha insertado nada porque todos los campos estaban vacíos.";
        }
    }

    // -------------------------------------------------
    // 2) ACTUALIZAR POR TÍTULO
    // -------------------------------------------------
    if ($accion === "actualizar") {

        $tituloBuscado = trim($_POST["titulo_busqueda"] ?? "");
        $nuevoTitulo = trim($_POST["titulo"] ?? "");
        $precio = $_POST["precio"] ?? "";
        $fecha_lanzamiento = trim($_POST["fecha_lanzamiento"] ?? "");
        $genero = trim($_POST["genero"] ?? "");
        $pegi = $_POST["pegi"] ?? "";
        $motor = trim($_POST["motor"] ?? "");

        if ($tituloBuscado !== "") {

            $camposActualizar = [];

            if ($nuevoTitulo !== "") {
                $camposActualizar["titulo"] = $nuevoTitulo;
            }

            if ($precio !== "") {
                $camposActualizar["precio"] = (float)$precio;
            }

            if ($fecha_lanzamiento !== "") {
                $camposActualizar["fecha_lanzamiento"] = $fecha_lanzamiento;
            }

            if ($genero !== "") {
                $camposActualizar["genero"] = $genero;
            }

            if ($pegi !== "") {
                $camposActualizar["pegi"] = (int)$pegi;
            }

            if ($motor !== "") {
                $camposActualizar["motor"] = $motor;
            }

            if (!empty($camposActualizar)) {

                $resultado = $collection->updateOne(
                    ["titulo" => $tituloBuscado],
                    ['$set' => $camposActualizar]
                );

                if ($resultado->getMatchedCount() > 0) {

                    $tituloFinal = $nuevoTitulo !== "" ? $nuevoTitulo : $tituloBuscado;

                    $documentoActualizado = $collection->findOne([
                        "titulo" => $tituloFinal
                    ]);

                    $mensaje = "Documento actualizado correctamente.";
                } else {
                    $mensaje = "No se encontró ningún documento con ese título.";
                }

            } else {
                $mensaje = "No se ha actualizado nada porque no se indicó ningún campo nuevo.";
            }

        } else {
            $mensaje = "Debes indicar el título del documento que quieres actualizar.";
        }
    }

    // -------------------------------------------------
    // 3) ELIMINAR POR TÍTULO
    // -------------------------------------------------
    if ($accion === "eliminar") {

        $tituloBuscado = trim($_POST["titulo_busqueda"] ?? "");

        if ($tituloBuscado !== "") {

            $resultado = $collection->deleteOne([
                "titulo" => $tituloBuscado
            ]);

            if ($resultado->getDeletedCount() > 0) {
                $mensaje = "Documento eliminado correctamente.";
            } else {
                $mensaje = "No se encontró ningún documento con ese título.";
            }

        } else {
            $mensaje = "Debes indicar el título del documento que quieres eliminar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD MongoDB con PHP</title>
</head>
<body>

    <h1>CRUD básico MongoDB con PHP</h1>

    <?php if ($mensaje !== ""): ?>
        <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
    <?php endif; ?>

    <hr>

    <h2>Insertar juego</h2>
    <form method="post">
        <input type="hidden" name="accion" value="insertar">

        <label>Título:</label><br>
        <input type="text" name="titulo"><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio"><br><br>

        <label>Fecha lanzamiento:</label><br>
        <input type="text" name="fecha_lanzamiento" placeholder="2020-12-10"><br><br>

        <label>Género:</label><br>
        <input type="text" name="genero"><br><br>

        <label>PEGI:</label><br>
        <input type="number" name="pegi"><br><br>

        <label>Motor:</label><br>
        <input type="text" name="motor"><br><br>

        <button type="submit">Insertar</button>
    </form>

    <hr>

    <h2>Actualizar juego por título</h2>
    <form method="post">
        <input type="hidden" name="accion" value="actualizar">

        <label>Título del documento a actualizar:</label><br>
        <input type="text" name="titulo_busqueda"><br><br>

        <label>Nuevo título:</label><br>
        <input type="text" name="titulo"><br><br>

        <label>Nuevo precio:</label><br>
        <input type="number" step="0.01" name="precio"><br><br>

        <label>Nueva fecha lanzamiento:</label><br>
        <input type="text" name="fecha_lanzamiento"><br><br>

        <label>Nuevo género:</label><br>
        <input type="text" name="genero"><br><br>

        <label>Nuevo PEGI:</label><br>
        <input type="number" name="pegi"><br><br>

        <label>Nuevo motor:</label><br>
        <input type="text" name="motor"><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <hr>

    <h2>Eliminar juego por título</h2>
    <form method="post">
        <input type="hidden" name="accion" value="eliminar">

        <label>Título del documento a eliminar:</label><br>
        <input type="text" name="titulo_busqueda"><br><br>

        <button type="submit">Eliminar</button>
    </form>

    <hr>

    <?php if ($documentoActualizado !== null): ?>
        <h2>Documento actualizado</h2>

        <?php
        if (isset($documentoActualizado["_id"])) {
            echo "ID: " . $documentoActualizado["_id"] . "<br>";
        }

        if (isset($documentoActualizado["titulo"])) {
            echo "Título: " . $documentoActualizado["titulo"] . "<br>";
        }

        if (isset($documentoActualizado["precio"])) {
            echo "Precio: " . $documentoActualizado["precio"] . "<br>";
        }

        if (isset($documentoActualizado["fecha_lanzamiento"])) {
            echo "Fecha lanzamiento: " . $documentoActualizado["fecha_lanzamiento"] . "<br>";
        }

        if (isset($documentoActualizado["genero"])) {
            echo "Género: " . $documentoActualizado["genero"] . "<br>";
        }

        if (isset($documentoActualizado["pegi"])) {
            echo "PEGI: " . $documentoActualizado["pegi"] . "<br>";
        }

        if (isset($documentoActualizado["motor"])) {
            echo "Motor: " . $documentoActualizado["motor"] . "<br>";
        }
        ?>
    <?php endif; ?>

</body>
</html>