<?php
// Devolveremos JSON siempre
header("Content-Type: application/json; charset=utf-8");

// --- CONFIG DB (ajusta si procede) ---
$host = "127.0.0.1";
$port = "3307";
$dbname = "videojuegos_asir";
$user = "root";
$pass = "";

// --- Leer JSON del body ---
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// --- Validar parámetros ---
$precioMax = (float)$data["precio_max"] ?? null;
$fechaMin  = $data["fecha_min"] ?? null;

try {
  // --- Conexión PDO ---
  $pdo = new PDO(
    "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
    $user,
    $pass
  );
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // --- Consulta segura con prepare() ---
  // Excluimos registros con precio_base o fecha_lanzamiento NULL
  $sql = "
    SELECT id_videojuego, titulo, fecha_lanzamiento, precio_base
    FROM videojuego
    WHERE precio_base IS NOT NULL
      AND fecha_lanzamiento IS NOT NULL
      AND precio_base < :precio_max
      AND fecha_lanzamiento > :fecha_min
    ORDER BY fecha_lanzamiento ASC, precio_base ASC
  ";

  $stmt = $pdo->prepare($sql);

  $stmt->execute([
    ':precio_max' => $precioMax,
    ':fecha_min'  => $fechaMin
  ]);

  $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    "ok" => true,
    "filtro" => [
      "precio_max" => $precioMax,
      "fecha_min" => $fechaMin
    ],
    "total" => count($juegos),
    "juegos" => $juegos
  ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    "error" => "Error DB",
    "detalle" => $e->getMessage()
  ], JSON_PRETTY_PRINT);
}
