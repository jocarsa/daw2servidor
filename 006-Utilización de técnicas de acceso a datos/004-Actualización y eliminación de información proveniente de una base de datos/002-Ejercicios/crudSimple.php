<?php

$host = "127.0.0.1";
$port = "3307";
$dbname = "videojuegos_asir";
$user = "root";
$pass = "";

$mensaje = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $accion = $_POST["accion"] ?? "";

        // -------------------
        // AÑADIR
        // -------------------
        if ($accion === "add") {

            $stmt = $pdo->prepare("
                INSERT INTO videojuego (titulo, precio_base, es_multijugador)
                VALUES (:titulo, :precio, 0)
            ");

            $stmt->execute([
                ':titulo' => $_POST["titulo"],
                ':precio' => $_POST["precio"]
            ]);

            $mensaje = "Videojuego añadido correctamente.";
        }

        // -------------------
        // ACTUALIZAR
        // -------------------
        if ($accion === "update") {

            $stmt = $pdo->prepare("
                UPDATE videojuego
                SET titulo = :titulo,
                    precio_base = :precio
                WHERE id_videojuego = :id
            ");

            $stmt->execute([
                ':id'     => $_POST["id"],
                ':titulo' => $_POST["titulo"],
                ':precio' => $_POST["precio"]
            ]);

            $mensaje = "Videojuego actualizado.";
        }

        // -------------------
        // ELIMINAR
        // -------------------
        if ($accion === "delete") {

            $stmt = $pdo->prepare("
                DELETE FROM videojuego
                WHERE id_videojuego = :id
            ");

            $stmt->execute([
                ':id' => $_POST["id"]
            ]);

            $mensaje = "Videojuego eliminado.";
        }
    }

} catch (PDOException $e) {
    $mensaje = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>CRUD simple en PHP</title>
</head>
<body>

<h2>CRUD sencillo - Tabla videojuego</h2>

<?php if ($mensaje): ?>
    <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
<?php endif; ?>

<form method="POST">

    <label>ID (para actualizar/eliminar):</label><br>
    <input type="number" name="id"><br><br>

    <label>Título:</label><br>
    <input type="text" name="titulo"><br><br>

    <label>Precio:</label><br>
    <input type="number" step="0.01" name="precio"><br><br>

    <button type="submit" name="accion" value="add">Añadir</button>
    <button type="submit" name="accion" value="update">Actualizar</button>
    <button type="submit" name="accion" value="delete">Eliminar</button>

</form>

</body>
</html>