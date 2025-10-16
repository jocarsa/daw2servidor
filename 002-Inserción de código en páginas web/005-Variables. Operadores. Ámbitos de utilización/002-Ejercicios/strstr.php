<?php
$email = "usuario@ejemplo.com";

$desdeArroba = strstr($email, "@");

$antesArroba = strstr($email, "@", true);

$usuario = $antesArroba;
$dominio = substr($desdeArroba, 1);

echo "Correo completo: " . $email . "\n";
echo "Parte antes de '@': " . $antesArroba . "\n";
echo "Parte desde '@': " . $desdeArroba . "\n";
echo "Solo dominio: " . $dominio . "\n";
?>
