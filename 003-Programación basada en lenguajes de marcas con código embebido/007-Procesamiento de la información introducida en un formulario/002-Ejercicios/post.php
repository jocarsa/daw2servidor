<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados por POST
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    echo "<h2>Datos recibidos:</h2>";
    echo "Nombre: " . $nombre . "<br>";
    echo "Email: " . $email . "<br>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo POST en PHP</title>
</head>
<body>
    <h1>Formulario de ejemplo usando POST</h1>
    <form method="post" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>
