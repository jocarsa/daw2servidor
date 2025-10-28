<?php
// Inicia la sesión
session_start();

// Guarda datos en la sesión
$_SESSION['usuario'] = 'Juan Pérez';
$_SESSION['rol'] = 'administrador';

// Muestra un mensaje
echo "Sesión iniciada correctamente.<br>";
echo "<a href='perfil.php'>Ir al perfil</a>";
?>
