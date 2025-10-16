<?php
$texto = "   Hola Mundo   ";

echo "[" . trim($texto) . "]\n";
echo "[" . ltrim($texto) . "]\n";
echo "[" . rtrim($texto) . "]\n";


$cadena = "---Hola Mundo---";
echo trim($cadena, "-");
?>
