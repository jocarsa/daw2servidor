<?php
session_start();

// Solo cerramos la sesión del usuario actual, sin eliminar los arrays globales
unset($_SESSION["usuario"]);
unset($_SESSION["rol"]);

setcookie("auth", "", time() - 3600, "/");
header("Location: roles.php");
exit;
