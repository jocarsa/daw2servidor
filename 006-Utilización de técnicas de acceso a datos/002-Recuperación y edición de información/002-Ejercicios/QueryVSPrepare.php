<?php

$host = "127.0.0.1";
$port = "3307";
$dbname = "videojuegos_asir";
$user = "root";
$pass = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>Prueba de inyección SQL</h2>";

    // Obtener id por GET
    $id = $_GET['id'] ?? '';

    echo "<h3>ID recibido:</h3> " . htmlspecialchars($id) . "<br><br>";

    echo "<hr>";
    echo "<h3>Método INSEGURO con query()</h3>";

    // MÉTODO INSEGURO
    $sql_inseguro = "SELECT * FROM videojuego WHERE id_videojuego = $id";

    echo "<strong>Consulta generada:</strong><br>";
    echo $sql_inseguro . "<br><br>";

    try {
        $stmt = $pdo->query($sql_inseguro);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $juego) {
            echo "ID: " . $juego['id_videojuego'] . " - ";
            echo "Título: " . $juego['titulo'] . "<br>";
        }

        if (count($resultados) === 0) {
            echo "Sin resultados.<br>";
        }

    } catch (PDOException $e) {
        echo "Error en query(): " . $e->getMessage();
    }

    echo "<hr>";
    echo "<h3>Método SEGURO con prepare()</h3>";

    // MÉTODO SEGURO
    $sql_seguro = "SELECT * FROM videojuego WHERE id_videojuego = ?";

    echo "<strong>Consulta preparada:</strong><br>";
    echo $sql_seguro . "<br><br>";

    $stmt2 = $pdo->prepare($sql_seguro);
    $stmt2->execute([$id]);
    $resultados2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados2 as $juego) {
        echo "ID: " . $juego['id_videojuego'] . " - ";
        echo "Título: " . $juego['titulo'] . "<br>";
    }

    if (count($resultados2) === 0) {
        echo "Sin resultados.<br>";
    }

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// con ?id=1 funciona igual en ambos casos
// con ?id=1 OR 1=1 se rompe todo
?>
