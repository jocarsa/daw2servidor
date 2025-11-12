<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: roles.php");
    exit;
}

$rol = $_SESSION["rol"];
$permisos = $_SESSION["permisos"];
$usuarios = $_SESSION["usuarios"];

if (!$permisos[$rol]["buscar_usuario"]) {
    echo "<p>No tienes permiso para buscar usuarios.</p>";
    exit;
}

$resultado = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $buscado = trim($_POST["nombre"]);
    $encontrado = null;
    foreach ($usuarios as $u) {
        if (strcasecmp($u["usuario"], $buscado) === 0) {
            $encontrado = $u;
            break;
        }
    }

    if ($encontrado) {
        $resultado = "Usuario: {$encontrado['usuario']} | Rol: {$encontrado['rol']}";
    } else {
        $resultado = "No se encontró el usuario '$buscado'.";
    }
}
?>

<h1>Buscar usuario</h1>
<form method="post" action="">
    <label>Nombre del usuario:</label>
    <input type="text" name="nombre" required><br><br>
    <input type="submit" value="Buscar">
</form>

<?php if ($resultado): ?>
<p><?php echo htmlspecialchars($resultado); ?></p>
<?php endif; ?>

<p><a href="panel.php">Volver al panel</a></p>
