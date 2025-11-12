<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: roles.php");
    exit;
}

$rol = $_SESSION["rol"];
$permisos = $_SESSION["permisos"];

if (!$permisos[$rol]["modificar_permisos"]) {
    echo "<p>No tienes permiso para modificar permisos.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rol_objetivo = $_POST["rol"];
    $permiso = $_POST["permiso"];
    $valor = $_POST["valor"];
    $_SESSION["permisos"][$rol_objetivo][$permiso] = ($valor === "true");
    echo "<p>Permiso '$permiso' del rol '$rol_objetivo' cambiado a '$valor'.</p>";
}
?>

<h1>Modificar permisos</h1>
<form method="post" action="">
    <label>Rol a modificar:</label>
    <select name="rol">
        <option value="admin">Administrador</option>
        <option value="director">Director</option>
        <option value="profesor">Profesor</option>
        <option value="alumno">Alumno</option>
    </select><br><br>

    <label>Permiso:</label>
    <select name="permiso">
        <option value="saludo">Saludo</option>
        <option value="añadir_usuario">Añadir usuario</option>
        <option value="buscar_usuario">Buscar usuario</option>
        <option value="modificar_permisos">Modificar permisos</option>
    </select><br><br>

    <label>Valor:</label>
    <select name="valor">
        <option value="true">True</option>
        <option value="false">False</option>
    </select><br><br>

    <input type="submit" value="Guardar cambios">
</form>
<p><a href="panel.php">Volver al panel</a></p>
