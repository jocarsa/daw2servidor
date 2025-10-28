<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

echo "Sesión cerrada correctamente.<br>";
echo "<a href='inicio.php'>Volver al inicio</a>";
?>
