<?php
session_start();

// Solo cerramos la sesión del usuario actual, sin eliminar los arrays globales
// session_destroy(); si quisieramos cargarnos toda la sesión, pero como utilizamos variables de sesión para pasarnos el array de usuarios y el de roles no podemos hacerlo así.
unset($_SESSION["usuario"]);
unset($_SESSION["rol"]);

setcookie("auth", "", time() - 3600, "/");
header("Location: roles.php");
exit;
