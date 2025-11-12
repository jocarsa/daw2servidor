<?php
// ----------------------------------------------------
// INICIO DE SESIÓN
// ----------------------------------------------------
session_start();

// ----------------------------------------------------
// ARRAYS BASE (solo para inicialización si no existen)
// ----------------------------------------------------
$usuarios_iniciales = [
    ["usuario" => "admin", "contrasena" => "admin123", "rol" => "admin"],
    ["usuario" => "ana", "contrasena" => "ana123", "rol" => "director"],
    ["usuario" => "luis", "contrasena" => "luis123", "rol" => "profesor"],
    ["usuario" => "maria", "contrasena" => "maria123", "rol" => "alumno"]
];

$permisos_iniciales = [
    "admin" => [
        "saludo" => true,
        "añadir_usuario" => true,
        "buscar_usuario" => true,
        "modificar_permisos" => true
    ],
    "director" => [
        "saludo" => true,
        "añadir_usuario" => true,
        "buscar_usuario" => true,
        "modificar_permisos" => false
    ],
    "profesor" => [
        "saludo" => true,
        "añadir_usuario" => false,
        "buscar_usuario" => true,
        "modificar_permisos" => false
    ],
    "alumno" => [
        "saludo" => true,
        "añadir_usuario" => false,
        "buscar_usuario" => false,
        "modificar_permisos" => false
    ]
];

// ----------------------------------------------------
// SOLO CREAMOS VARIABLES DE SESIÓN SI NO EXISTEN
// ----------------------------------------------------
if (!isset($_SESSION["usuarios"])) {
    $_SESSION["usuarios"] = $usuarios_iniciales;
}
if (!isset($_SESSION["permisos"])) {
    $_SESSION["permisos"] = $permisos_iniciales;
}

// ----------------------------------------------------
// FUNCIONES AUXILIARES
// ----------------------------------------------------
function crearCookieUsuario($usuario, $rol) {
    $datos = json_encode(["usuario" => $usuario, "rol" => $rol]);
    setcookie("auth", $datos, time() + 3600, "/");
}
function eliminarCookieUsuario() {
    setcookie("auth", "", time() - 3600, "/");
}

// ----------------------------------------------------
// LOGIN
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuario"], $_POST["contrasena"])) {
    $usuario = trim($_POST["usuario"]);
    $contrasena = trim($_POST["contrasena"]);
    $usuarios = $_SESSION["usuarios"];

    foreach ($usuarios as $u) {
        if ($u["usuario"] === $usuario && $u["contrasena"] === $contrasena) {
            crearCookieUsuario($usuario, $u["rol"]);
            header("Location: roles.php");
            exit;
        }
    }

    echo "<p>Usuario o contraseña incorrectos.</p>";
}

// ----------------------------------------------------
// MIGRAR COOKIE A SESIÓN
// ----------------------------------------------------
if (!isset($_SESSION["usuario"]) && isset($_COOKIE["auth"])) {
    $datos = json_decode($_COOKIE["auth"], true);
    if ($datos && isset($datos["usuario"], $datos["rol"])) {
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["rol"] = $datos["rol"];
        eliminarCookieUsuario();
    }
}

// ----------------------------------------------------
// CERRAR SESIÓN (solo el usuario actual)
// ----------------------------------------------------
if (isset($_GET["logout"])) {
    unset($_SESSION["usuario"]);
    unset($_SESSION["rol"]);
    eliminarCookieUsuario();
    header("Location: roles.php");
    exit;
}

// ----------------------------------------------------
// VARIABLES DE SESIÓN
// ----------------------------------------------------
$usuario = $_SESSION["usuario"] ?? null;
$rol = $_SESSION["rol"] ?? null;
?>

<h1>Aplicación de Roles con Permisos y Usuarios Dinámicos</h1>

<?php if (!$usuario): ?>
<form method="post" action="">
    <label>Usuario:</label><br>
    <input type="text" name="usuario" required><br><br>
    <label>Contraseña:</label><br>
    <input type="password" name="contrasena" required><br><br>
    <button type="submit">Entrar</button>
</form>

<?php else: ?>
<p>👋 Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong> (rol: <?php echo htmlspecialchars($rol); ?>)</p>
<p><a href="roles.php?logout=1">Cerrar sesión</a></p>
<p><a href="panel.php">Ir al panel principal</a></p>
<?php endif; ?>
