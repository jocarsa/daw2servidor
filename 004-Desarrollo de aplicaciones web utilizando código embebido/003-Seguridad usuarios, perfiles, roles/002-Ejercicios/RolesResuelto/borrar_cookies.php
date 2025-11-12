<?php
if (isset($_COOKIE)) {
    foreach ($_COOKIE as $nombre => $valor) {
        setcookie($nombre, "", time() - 3600, "/");
    }
    echo "<p>Cookies borradas correctamente.</p>";
} else {
    echo "<p>No hay cookies que borrar.</p>";
}
?>
<p><a href="roles.php">Volver al inicio</a></p>
