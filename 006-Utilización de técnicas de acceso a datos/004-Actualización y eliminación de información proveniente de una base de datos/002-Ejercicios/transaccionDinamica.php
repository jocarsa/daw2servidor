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

        $idDesarrollador = $_POST["id_desarrollador"] ?? "";
        $campo = $_POST["campo"] ?? "";
        $valor = $_POST["valor"] ?? "";

        // Solo permitimos actualizar estas columnas
        $camposPermitidos = [
            "nombre",
            "apellido",
            "email",
            "ciudad",
            "pais",
            "activo"
        ];

        if ($idDesarrollador !== "" && $campo !== "" && $valor !== "") {

            if (in_array($campo, $camposPermitidos)) {

                // Iniciar transacción
                $pdo->beginTransaction();

                // Si el campo es "activo", lo convertimos a entero
                if ($campo === "activo") {
                    $valor = (int)$valor;
                }

                // UPDATE dinámico de una sola columna
                $sqlUpdate = "
                    UPDATE desarrollador
                    SET $campo = :valor
                    WHERE id_desarrollador = :id_desarrollador
                ";

                $stmtUpdate = $pdo->prepare($sqlUpdate);

                $stmtUpdate->execute([
                    ":valor" => $valor,
                    ":id_desarrollador" => (int)$idDesarrollador
                ]);

                // SELECT completo dentro de la transacción
                $stmtSelect = $pdo->query("
                    SELECT id_desarrollador, nombre, apellido, email, ciudad, pais, activo
                    FROM desarrollador
                    ORDER BY id_desarrollador ASC
                ");

                $desarrolladores = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

                // Confirmar transacción
                $pdo->commit();

                $mensaje = "Transacción realizada correctamente. Se ha actualizado el campo '$campo'.";
            } else {
                $mensaje = "El campo seleccionado no está permitido.";
            }

        } else {
            $mensaje = "Debes rellenar todos los campos del formulario.";
        }
    }

} catch (PDOException $e) {

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
<title>Transacción con UPDATE dinámico</title>
</head>
<body>

<h2>Actualizar un único campo con transacción</h2>

<?php if ($mensaje): ?>
    <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
<?php endif; ?>

<form method="POST">

    <label>ID del desarrollador:</label><br>
    <input type="number" name="id_desarrollador" required><br><br>

    <label>Campo a actualizar:</label><br>
    <select name="campo" required>
        <option value="">-- Selecciona un campo --</option>
        <option value="nombre">nombre</option>
        <option value="apellido">apellido</option>
        <option value="email">email</option>
        <option value="ciudad">ciudad</option>
        <option value="pais">pais</option>
        <option value="activo">activo</option>
    </select><br><br>

    <label>Nuevo valor:</label><br>
    <input type="text" name="valor" required><br><br>

    <button type="submit">Actualizar con transacción</button>
</form>

<?php if (!empty($desarrolladores)): ?>

    <h3>Tabla completa tras la transacción:</h3>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Ciudad</th>
            <th>País</th>
            <th>Activo</th>
        </tr>

        <?php foreach ($desarrolladores as $dev): ?>
            <tr>
                <td><?php echo htmlspecialchars($dev["id_desarrollador"]); ?></td>
                <td><?php echo htmlspecialchars($dev["nombre"]); ?></td>
                <td><?php echo htmlspecialchars($dev["apellido"]); ?></td>
                <td><?php echo htmlspecialchars($dev["email"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($dev["ciudad"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($dev["pais"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($dev["activo"]); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

</body>
</html>