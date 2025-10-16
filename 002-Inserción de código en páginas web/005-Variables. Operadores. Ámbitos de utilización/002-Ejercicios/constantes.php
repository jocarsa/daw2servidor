<?php

define("PI", 3.1416);
define("MENSAJE", "Bienvenido al programa");

echo MENSAJE . "\n"; 
echo "El valor de PI es: " . PI . "\n";

function mostrarConstante() {
    echo "Usando constante dentro de función: " . PI . "\n";
}

mostrarConstante();

const VERSION = "1.0.0";

echo "Versión del programa: " . VERSION . "\n";
?>