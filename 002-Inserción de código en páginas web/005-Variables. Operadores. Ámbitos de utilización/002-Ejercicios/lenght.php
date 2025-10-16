<?php
$mensaje = "Programar en PHP es divertido";

$longitud = strlen($mensaje);

echo "El mensaje es: '$mensaje'\n";
echo "Tiene $longitud caracteres.\n";

$password = "abc123";

if (strlen($password) < 8) {
    echo "La contraseña es demasiado corta.\n";
} else {
    echo "La contraseña tiene una longitud segura.\n";
}
?>
