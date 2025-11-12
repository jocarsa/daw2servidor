<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: roles.php");
    exit;
}

$usuario = $_SESSION["usuario"];
$rol = $_SESSION["rol"];
$permisos = $_SESSION["permisos"];

if (!$permisos[$rol]["saludo"]) {
    echo "<p>No tienes permiso para acceder a esta función.</p>";
    exit;
}

echo "<h1>¡Hola, $usuario!</h1>";
echo "<p>Bienvenido al sistema de roles y permisos.</p>";
echo "<p><a href='panel.php'>Volver al panel</a></p>";
