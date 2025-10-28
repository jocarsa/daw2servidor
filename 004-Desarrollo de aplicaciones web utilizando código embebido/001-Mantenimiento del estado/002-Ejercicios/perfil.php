<?php
// Reanuda la sesión existente
session_start();

// Verifica si la variable de sesión existe
if (isset($_SESSION['usuario'])) {
    echo "Bienvenido, " . $_SESSION['usuario'] . "<br>";
    echo "Tu rol es: " . $_SESSION['rol'] . "<br>";
    echo "<a href='cerrar.php'>Cerrar sesión</a>";
} else {
    echo "No has iniciado sesión.<br>";
    echo "<a href='inicio.php'>Iniciar sesión</a>";
}
?>
