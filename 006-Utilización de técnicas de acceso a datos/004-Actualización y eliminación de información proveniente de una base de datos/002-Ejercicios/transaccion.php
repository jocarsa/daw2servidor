<?php

$host = "127.0.0.1";
$port = "3307";
$dbname = "videojuegos_asir";
$user = "root";
$pass = "";

$mensaje = "";
$desarrolladores = [];

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $accion = $_POST["accion"] ?? "";

        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar nuevo desarrollador
        $stmtInsert = $pdo->prepare("
            INSERT INTO desarrollador 
            (nombre, apellido, fecha_alta, activo)
            VALUES (:nombre, 'Temporal', CURDATE(), 1)
        ");

        $stmtInsert->execute([
            ':nombre' => $_POST["nombre"]
        ]);

        // Hacer SELECT dentro de la transacción
        $stmtSelect = $pdo->query("
            SELECT id_desarrollador, nombre, apellido, fecha_alta
            FROM desarrollador
            ORDER BY id_desarrollador DESC
        ");

        $desarrolladores = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

        // Commit o Rollback según botón
        if ($accion === "commit") {
            $pdo->commit();
            $mensaje = "Transacción confirmada (COMMIT).";
        } else {
            $pdo->rollBack();
            $mensaje = "Transacción cancelada (ROLLBACK).";
        }
    }

} catch (PDOException $e) {

    // Si ocurre error, revertir cambios
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $mensaje = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Demo Transacciones</title>
</head>
<body>

<h2>Transacción con Commit o Rollback</h2>

<?php if ($mensaje): ?>
    <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
<?php endif; ?>

<form method="POST">
    <label>Nombre del desarrollador:</label><br>
    <input type="text" name="nombre" required><br><br>

    <button type="submit" name="accion" value="commit">
        Insertar + Commit
    </button>

    <button type="submit" name="accion" value="rollback">
        Insertar + Rollback
    </button>
</form>

<?php if (!empty($desarrolladores)): ?>

    <h3>Resultado del SELECT dentro de la transacción:</h3>
    <ul>
        <?php foreach ($desarrolladores as $dev): ?>
            <li>
                #<?php echo $dev["id_desarrollador"]; ?> —
                <?php echo htmlspecialchars($dev["nombre"]); ?> —
                <?php echo htmlspecialchars($dev["fecha_alta"]); ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

</body>
</html>