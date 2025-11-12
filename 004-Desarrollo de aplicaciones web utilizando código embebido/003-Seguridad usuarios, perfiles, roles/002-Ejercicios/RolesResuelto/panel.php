<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: roles.php");
    exit;
}

$usuario = $_SESSION["usuario"];
$rol = $_SESSION["rol"];
$permisos = $_SESSION["permisos"];
?>

<h1>Panel principal</h1>
<p>Usuario: <strong><?php echo $usuario; ?></strong> (Rol: <?php echo $rol; ?>)</p>

<ul>
    <?php if ($permisos[$rol]["saludo"]) { ?>
        <li><a href="saluda.php">Saludar</a></li>
    <?php } ?>
    <?php if ($permisos[$rol]["añadir_usuario"]) { ?>
        <li><a href="añadir_usuario.php">Añadir usuario</a></li>
    <?php } ?>
    <?php if ($permisos[$rol]["buscar_usuario"]) { ?>
        <li><a href="buscar_usuario.php">Buscar usuario</a></li>
    <?php } ?>
    <?php if ($permisos[$rol]["modificar_permisos"]) { ?>
        <li><a href="modificar_permisos.php">Modificar permisos</a></li>
    <?php } ?>
</ul>

<p><a href="cerrar_sesion.php">Cerrar sesión</a></p>
<p><a href="borrar_cookies.php">Borrar cookies</a></p>
