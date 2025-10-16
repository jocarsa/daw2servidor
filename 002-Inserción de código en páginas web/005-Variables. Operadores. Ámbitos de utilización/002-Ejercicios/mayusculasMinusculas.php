<?php
$frase = "Hola Mundo desde PHP";

echo "Original: " . $frase . "\n";
echo "En minúsculas: " . strtolower($frase) . "\n";
echo "En mayúsculas: " . strtoupper($frase) . "\n";

$nombre1 = "Carlos";
$nombre2 = "cARLOS";

if (strtolower($nombre1) === strtolower($nombre2)) {
    echo "Los nombres coinciden (sin importar mayúsculas/minúsculas)\n";
}
?>
