<?php
// PHP CREA LA COOKIE ANTES DEL HTML
setcookie("usuario", "Juan Pérez desde PHP", time() + (7 * 24 * 60 * 60), "/");
// Cookie válida por 7 días en todo el sitio ("/")
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ejemplo de Cookies PHP → JavaScript</title>
</head>
<body>
  <h1>Ejemplo de manejo de cookies (PHP y JavaScript)</h1>

  <button id="guardar">Guardar cookie con JS</button>
  <button id="leer">Leer cookie</button>
  <button id="borrar">Borrar cookie</button>

  <p id="resultado"></p>

  <script>
    function setCookie(nombre, valor, dias) {
      const fecha = new Date();
      fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
      const expiracion = "expires=" + fecha.toUTCString();
      document.cookie = nombre + "=" + encodeURIComponent(valor) + ";" + expiracion + ";path=/";
    }

    function getCookie(nombre) {
      const nombreEQ = nombre + "=";
      const cookies = document.cookie.split(';');
      for (let i = 0; i < cookies.length; i++) {
        let c = cookies[i].trim();
        if (c.indexOf(nombreEQ) === 0) {
          return decodeURIComponent(c.substring(nombreEQ.length));
        }
      }
      return null;
    }

    function deleteCookie(nombre) {
      document.cookie = nombre + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    document.getElementById("guardar").addEventListener("click", () => {
      setCookie("usuario", "Valor guardado desde JavaScript", 7);
      document.getElementById("resultado").textContent = "Cookie guardada con JS.";
    });

    document.getElementById("leer").addEventListener("click", () => {
      const valor = getCookie("usuario");
      document.getElementById("resultado").textContent =
        valor ? `Cookie encontrada: ${valor}` : "No hay cookie guardada.";
    });

    document.getElementById("borrar").addEventListener("click", () => {
      deleteCookie("usuario");
      document.getElementById("resultado").textContent = "Cookie eliminada.";
    });
  </script>
</body>
</html>
