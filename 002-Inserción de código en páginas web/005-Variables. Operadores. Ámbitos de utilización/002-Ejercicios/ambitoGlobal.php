<?php

$mensaje = "Hola desde el ámbito global";


function mostrarMensajeInvalido() {
    // Aquí $mensaje no existe en el ámbito local
    echo "Intento de acceso directo: " . $mensaje . "\n";
}


function funcionLocal() {
    $mensaje = "Hola desde el ámbito local";
    echo "Variable local: " . $mensaje . "\n";
}


function mostrarMensajeGlobal() {
    echo "Acceso mediante \$GLOBALS: " . $GLOBALS["mensaje"] . "\n";
}

mostrarMensajeInvalido();
funcionLocal();
mostrarMensajeGlobal();
?>