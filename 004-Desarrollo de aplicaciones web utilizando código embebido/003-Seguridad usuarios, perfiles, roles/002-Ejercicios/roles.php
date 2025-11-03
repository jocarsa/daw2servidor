<?php
// ----------------------------------------------------
// INICIO DE SESIÓN
// ----------------------------------------------------
session_start();

// ----------------------------------------------------
// FUNCIONES AUXILIARES
// ----------------------------------------------------

// Crear cookie con datos de usuario
function crearCookieUsuario($usuario, $rol) {
    $datos = json_encode(["usuario" => $usuario, "rol" => $rol]);
    setcookie("auth", $datos, time() + 3600, "/"); // dura 1 hora
}

// Eliminar cookie
function eliminarCookieUsuario() {
    setcookie("auth", "", time() - 3600, "/");
}

// ----------------------------------------------------
// PROCESO DE LOGIN (desde formulario)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuario"], $_POST["rol"])) {
    $usuario = trim($_POST["usuario"]);
    $rol = $_POST["rol"];

    // Creamos cookie temporal
    crearCookieUsuario($usuario, $rol);

    // Redirigir para permitir que el navegador establezca la cookie
    header("Location: roles.php");
    exit;
}

// ----------------------------------------------------
// SI EXISTE COOKIE Y NO HAY SESIÓN, MIGRAR A SESIÓN
// ----------------------------------------------------
if (!isset($_SESSION["usuario"]) && isset($_COOKIE["auth"])) {
    $datos = json_decode($_COOKIE["auth"], true);
    if ($datos && isset($datos["usuario"], $datos["rol"])) {
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["rol"] = $datos["rol"];
        eliminarCookieUsuario(); // ya no necesitamos la cookie
    }
}

// ----------------------------------------------------
// CERRAR SESIÓN
// ----------------------------------------------------
if (isset($_GET["logout"])) {
    session_unset();     // eliminar variables de sesión
    session_destroy();   // destruir la sesión
    eliminarCookieUsuario(); // eliminar también cualquier cookie vieja
    header("Location: roles.php");
    exit;
}

// ----------------------------------------------------
// OBTENER DATOS DE SESIÓN (si existen)
// ----------------------------------------------------
$usuario = $_SESSION["usuario"] ?? null;
$rol = $_SESSION["rol"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo PHP: Cookies + Sesiones + Roles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { margin-bottom: 20px; }
        .admin { color: red; }
        .editor { color: green; }
        .visitante { color: blue; }
        .panel { border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Aplicación con Roles usando Cookies y Sesiones</h1>

<?php if (!$usuario): ?>

    <!-- FORMULARIO DE LOGIN -->
    <form method="post" action="">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Selecciona un rol:</label><br>
        <select name="rol" required>
            <option value="admin">Administrador</option>
            <option value="editor">Editor</option>
            <option value="visitante">Visitante</option>
        </select><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>

<?php else: ?>

    <!-- PANEL PRINCIPAL -->
    <p>👋 Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong> 
    (rol: <span class="<?php echo htmlspecialchars($rol); ?>"><?php echo htmlspecialchars($rol); ?></span>)</p>
    <p><a href="?logout=1">Cerrar sesión</a></p>

    <div class="panel">
        <?php if ($rol === "admin"): ?>
            <h2>Panel de Administrador</h2>
            <ul>
                <li>Gestionar usuarios</li>
                <li>Ver informes del sistema</li>
                <li>Configurar la aplicación</li>
            </ul>

        <?php elseif ($rol === "editor"): ?>
            <h2>Panel de Editor</h2>
            <ul>
                <li>Crear y editar contenido</li>
                <li>Publicar artículos</li>
            </ul>

        <?php else: ?>
            <h2>Panel de Visitante</h2>
            <ul>
                <li>Ver artículos públicos</li>
                <li>Suscribirse a noticias</li>
            </ul>
        <?php endif; ?>
    </div>

<?php endif; ?>

</body>
</html>
