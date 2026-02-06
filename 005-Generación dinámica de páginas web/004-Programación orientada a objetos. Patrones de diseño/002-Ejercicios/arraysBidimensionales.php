<?php
// Array bidimensional (matriz)
$matriz = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];

/* ===== Recorrido con for ===== */
$salidaFor = "";

for ($i = 0; $i < count($matriz); $i++) {
    for ($j = 0; $j < count($matriz[$i]); $j++) {
        $salidaFor .= "Elemento en [$i][$j] = " . $matriz[$i][$j] . "\n";
    }
}

/* ===== Recorrido con foreach ===== */
$salidaForeach = "";

foreach ($matriz as $i => $fila) {
    foreach ($fila as $j => $elemento) {
        $salidaForeach .= "Elemento en [$i][$j] = $elemento\n";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recorrido de Array Bidimensional en PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1>Recorrido de un array bidimensional en PHP</h1>

    <h2>Recorrido usando for</h2>
    <pre><?php echo htmlspecialchars($salidaFor); ?></pre>

    <h2>Recorrido usando foreach</h2>
    <pre><?php echo htmlspecialchars($salidaForeach); ?></pre>

</body>
</html>
