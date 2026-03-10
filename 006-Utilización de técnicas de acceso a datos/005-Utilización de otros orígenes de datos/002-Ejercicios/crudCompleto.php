<?php
require 'vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

$mensaje = "";

// Conectar con el servidor MongoDB
$client = new Client("mongodb://localhost:27017");

// Seleccionar base de datos
$db = $client->Videojuegos;

// Seleccionar colección
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
    // 2) ACTUALIZAR
    // -------------------------------------------------
    if ($accion === "actualizar") {

        $id = trim($_POST["id"] ?? "");
        $titulo = trim($_POST["titulo"] ?? "");
        $precio = $_POST["precio"] ?? "";
        $fecha_lanzamiento = trim($_POST["fecha_lanzamiento"] ?? "");
        $genero = trim($_POST["genero"] ?? "");
        $pegi = $_POST["pegi"] ?? "";
        $motor = trim($_POST["motor"] ?? "");

        if ($id !== "") {

            $camposActualizar = [];

            if ($titulo !== "") {
                $camposActualizar["titulo"] = $titulo;
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
                    ["_id" => new ObjectId($id)],
                    ['$set' => $camposActualizar]
                );

                $mensaje = "Documentos modificados: " . $resultado->getModifiedCount();
            } else {
                $mensaje = "No se ha actualizado nada porque no se indicó ningún campo nuevo.";
            }

        } else {
            $mensaje = "Debes indicar un ID para actualizar.";
        }
    }

    // -------------------------------------------------
    // 3) ELIMINAR
    // -------------------------------------------------
    if ($accion === "eliminar") {

        $id = trim($_POST["id"] ?? "");

        if ($id !== "") {
            $resultado = $collection->deleteOne([
                "_id" => new ObjectId($id)
            ]);

            $mensaje = "Documentos eliminados: " . $resultado->getDeletedCount();
        } else {
            $mensaje = "Debes indicar un ID para eliminar.";
        }
    }
}


// =====================================================
// OBTENER TODOS LOS DOCUMENTOS
// =====================================================
$cursor = $collection->find();

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

    <h2>Actualizar juego</h2>
    <form method="post">
        <input type="hidden" name="accion" value="actualizar">

        <label>ID del documento:</label><br>
        <input type="text" name="id"><br><br>

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

    <h2>Eliminar juego</h2>
    <form method="post">
        <input type="hidden" name="accion" value="eliminar">

        <label>ID del documento:</label><br>
        <input type="text" name="id"><br><br>

        <button type="submit">Eliminar</button>
    </form>

    <hr>

    <h2>Listado de juegos</h2>

    <?php foreach ($cursor as $juego): ?>

        <?php
        if (isset($juego["_id"])) {
            echo "ID: " . $juego["_id"] . "<br>";
        }

        if (isset($juego["titulo"])) {
            echo "Título: " . $juego["titulo"] . "<br>";
        }

        if (isset($juego["precio"])) {
            echo "Precio: " . $juego["precio"] . "<br>";
        }

        if (isset($juego["fecha_lanzamiento"])) {
            echo "Fecha lanzamiento: " . $juego["fecha_lanzamiento"] . "<br>";
        }

        if (isset($juego["genero"])) {
            echo "Género: " . $juego["genero"] . "<br>";
        }

        if (isset($juego["pegi"])) {
            echo "PEGI: " . $juego["pegi"] . "<br>";
        }

        if (isset($juego["motor"])) {
            echo "Motor: " . $juego["motor"] . "<br>";
        }

        echo "<hr>";
        ?>

    <?php endforeach; ?>

</body>
</html>