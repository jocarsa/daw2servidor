<?php

/*
=====================================================================
PASOS PARA USAR MONGODB CON PHP (RESUMEN COMPLETO)
=====================================================================

Instalar MongoDB Server
--------------------------------
Descargar desde:
https://www.mongodb.com/try/download/community

Instalar y arrancar el servidor.

Para arrancarlo manualmente desde terminal:

"& "C:\Program Files\MongoDB\Server\8.2\bin\mongod.exe" --dbpath C:\mongodb\data


Instalar extensión MongoDB en PHP
--------------------------------
Descargar la DLL correspondiente a tu versión de PHP desde: (ejecutar datos.php para comprobar version)
https://pecl.php.net/package/mongodb

Copiar el archivo:

php_mongodb.dll

a la carpeta:

C:\xampp\php\ext\


Activar extensión en php.ini
--------------------------------

Abrir:
C:\xampp\php\php.ini

Añadir la línea:

extension=mongodb

Reiniciar Apache desde XAMPP.

Instalar composer
--------------------------------

https://getcomposer.org/download/

Comprobar que la extensión funciona
--------------------------------

En terminal ejecutar:

php -m

Debe aparecer:

mongodb


Instalar Composer (gestor de dependencias)
--------------------------------

Ir a la carpeta del proyecto:

cd "C:\xampp\htdocs\daw2servidor\006-Utilización de técnicas de acceso a datos\005-Utilización de otros orígenes de datos\002-Ejercicios"

Ejecutar:

composer require mongodb/mongodb

Esto crea:

vendor/autoload.php


Usar el autoload de Composer
--------------------------------

Este archivo carga automáticamente todas las librerías instaladas.


Abrir el servidor
--------------------------------

Crear una carpeta donde se vaya a almacenar la base de datos. C:\mongodb\data

Ejecutar el siguiente comando para arrancar el servidor.

& "C:\Program Files\MongoDB\Server\8.2\bin\mongod.exe" --dbpath C:\mongodb\data
*/

require 'vendor/autoload.php';


use MongoDB\Client;


// Conectar con el servidor MongoDB
$client = new Client("mongodb://localhost:27017");


// Seleccionar base de datos
// Si no existe MongoDB la crea automáticamente
$db = $client->Videojuegos;


// Seleccionar colección
// En MongoDB una colección equivale a una tabla en SQL
$collection = $db->Juegos;


// Obtener todos los documentos de la colección
// Equivalente SQL:
// SELECT * FROM Juegos
$cursor = $collection->find();


// Recorrer los documentos obtenidos
foreach ($cursor as $juego) {

    if (isset($juego["_id"])) {
        echo "ID: " . $juego["_id"] . "<br>";
    }

    if (isset($juego["titulo"])) {
        echo "Título: " . $juego["titulo"] . "<br>";
    }

    if (isset($juego["precio"])) {
        echo "Precio: " . $juego["precio"] . "<br>";
    }

    if (isset($juego["fecha_lanzamiento"])) {
        echo "Fecha lanzamiento: " . $juego["fecha_lanzamiento"] . "<br>";
    }

    if (isset($juego["genero"])) {
        echo "Género: " . $juego["genero"] . "<br>";
    }

    if (isset($juego["pegi"])) {
        echo "PEGI: " . $juego["pegi"] . "<br>";
    }

    if (isset($juego["motor"])) {
        echo "Motor: " . $juego["motor"] . "<br>";
    }

    echo "<hr>";
}