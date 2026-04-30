<?php

function servicioAlumnos($metodo, $datosEntrada) {
    $archivo = "datos/alumnos.json";

    if ($metodo === "GET") {
        return consultarAlumnos($archivo);
    }

    if ($metodo === "POST") {
        return añadirAlumno($archivo, $datosEntrada);
    }

    return [
        "codigo" => 405,
        "datos" => [
            "error" => "Método no permitido para alumnos"
        ]
    ];
}


function consultarAlumnos($archivo) {
    $alumnos = leerJsonAlumnos($archivo);

    return [
        "codigo" => 200,
        "datos" => $alumnos
    ];
}


function añadirAlumno($archivo, $datosEntrada) {
    if (!isset($datosEntrada["nombre"]) || trim($datosEntrada["nombre"]) === "") {
        return [
            "codigo" => 400,
            "datos" => [
                "error" => "El nombre del alumno es obligatorio"
            ]
        ];
    }

    if (!isset($datosEntrada["curso"]) || trim($datosEntrada["curso"]) === "") {
        return [
            "codigo" => 400,
            "datos" => [
                "error" => "El curso del alumno es obligatorio"
            ]
        ];
    }

    $alumnos = leerJsonAlumnos($archivo);

    $nuevoAlumno = [
        "id" => generarNuevoIdAlumnos($alumnos),
        "nombre" => $datosEntrada["nombre"],
        "curso" => $datosEntrada["curso"]
    ];

    $alumnos[] = $nuevoAlumno;

    guardarJsonAlumnos($archivo, $alumnos);

    return [
        "codigo" => 201,
        "datos" => [
            "mensaje" => "Alumno añadido correctamente",
            "alumno" => $nuevoAlumno
        ]
    ];
}


function leerJsonAlumnos($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }

    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);

    if ($datos === null) {
        return [];
    }

    return $datos;
}


function guardarJsonAlumnos($archivo, $datos) {
    file_put_contents(
        $archivo,
        json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}


function generarNuevoIdAlumnos($datos) {
    $mayorId = 0;

    foreach ($datos as $elemento) {
        if ($elemento["id"] > $mayorId) {
            $mayorId = $elemento["id"];
        }
    }

    return $mayorId + 1;
}