<?php
$texto1 = "PHP";
$texto2 = "php";

$resultado1 = strcmp($texto1, $texto2);

$resultado2 = strcasecmp($texto1, $texto2);

echo "strcmp(): " . $resultado1 . "\n";
echo "strcasecmp(): " . $resultado2 . "\n";

if ($resultado1 === 0) {
    echo "Las cadenas son exactamente iguales.\n";
} else {
    echo "Las cadenas son diferentes.\n";
}
?>
