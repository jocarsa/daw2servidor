<?php
$correos = "ana@gmail.com,juan@yahoo.com,pedro@hotmail.com";

$listaCorreos = explode(",", $correos);

echo "Lista de correos:\n";
foreach ($listaCorreos as $correo) {
    echo "- " . trim($correo) . "\n";
}
?>