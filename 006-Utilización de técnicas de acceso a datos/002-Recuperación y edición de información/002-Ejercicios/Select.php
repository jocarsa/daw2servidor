<?php

$host = "127.0.0.1";
$port = "3307"; // le he cambiado el puerto. el default es el 3306
$dbname = "videojuegos_asir";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    ); // PHP Data Object. La conexión entre PHP y MySQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO lanza un error si algo falla.

    echo "<h2>Conexión correcta</h2>";

    $sql = "SELECT * FROM videojuego";
    $stmt = $pdo->query($sql);
    $videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Listado de videojuegos:</h3>";

    foreach ($videojuegos as $juego) {
        echo "ID: " . $juego['id_videojuego'] . "<br>";
        echo "Título: " . $juego['titulo'] . "<br>";
        echo "Fecha lanzamiento: " . $juego['fecha_lanzamiento'] . "<br>";
        echo "PEGI: " . $juego['pegi'] . "<br>";
        echo "Precio base: " . $juego['precio_base'] . "<br>";
        echo "Motor: " . $juego['motor'] . "<br>";
        echo "----------------------------<br><br>";
    }

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
