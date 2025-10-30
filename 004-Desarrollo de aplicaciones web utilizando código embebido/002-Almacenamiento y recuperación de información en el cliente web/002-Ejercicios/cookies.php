<?php
// ----- 1. Crear una cookie -----
// setcookie(nombre, valor, tiempo_de_expiración, ruta);
setcookie("usuario", "Juan Pérez", time() + 3600, "/"); 
// La cookie expirará en 1 hora

// ----- 2. Leer una cookie -----
if (isset($_COOKIE["usuario"])) {
    echo "👋 Bienvenido de nuevo, " . $_COOKIE["usuario"] . "!";
} else {
    echo "No se ha detectado ninguna cookie de usuario. Creando una...";
}

// ----- 3. Eliminar una cookie -----
// Para borrar una cookie, se establece con una fecha expirada
if (isset($_GET["borrar"])) {
    setcookie("usuario", "", time() - 3600, "/");
    echo "<br>La cookie ha sido eliminada.";
}
?>

<hr>
<a href="cookies.php">Refrescar página</a> |
<a href="cookies.php?borrar=1">Borrar cookie</a>
