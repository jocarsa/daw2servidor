<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: roles.php");
    exit;
}

$rol = $_SESSION["rol"];
$permisos = $_SESSION["permisos"];

if (!$permisos[$rol]["añadir_usuario"]) {
    echo "<p>No tienes permiso para añadir usuarios.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo = trim($_POST["nuevo_usuario"]);
    $nueva_contra = trim($_POST["nueva_contra"]);
    $nuevo_rol = $_POST["nuevo_rol"];

    // Recuperamos la lista actual de usuarios
    $usuarios = $_SESSION["usuarios"];

    // Comprobamos si ya existe
    $existe = false;
    foreach ($usuarios as $u) {
        if ($u["usuario"] === $nuevo) {
            $existe = true;
            break;
        }
    }

    if ($existe) {
        echo "<p>El usuario '$nuevo' ya existe.</p>";
    } else {
        $usuarios[] = ["usuario" => $nuevo, "contrasena" => $nueva_contra, "rol" => $nuevo_rol];
        $_SESSION["usuarios"] = $usuarios;
        echo "<p>Usuario '$nuevo' creado correctamente con rol '$nuevo_rol'.</p>";
    }
}
?>

<h1>Añadir usuario</h1>
<form method="post" action="">
    <label>Nombre de usuario:</label>
    <input type="text" name="nuevo_usuario" required><br><br>
    <label>Contraseña:</label>
    <input type="password" name="nueva_contra" required><br><br>
    <label>Rol:</label>
    <select name="nuevo_rol">
        <option value="admin">Administrador</option>
        <option value="director">Director</option>
        <option value="profesor">Profesor</option>
        <option value="alumno">Alumno</option>
    </select><br><br>
    <input type="submit" value="Crear usuario">
</form>
<p><a href="panel.php">Volver al panel</a></p>
