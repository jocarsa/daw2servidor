<?php

header("Content-Type: application/json; charset=utf-8");

$ofertas = [
    [
        "id" => 1,
        "titulo" => "Oferta RPG de la semana",
        "categoria" => "Rol",
        "descuento" => 20,
        "descripcion" => "Descuento especial en juegos de rol seleccionados."
    ],
    [
        "id" => 2,
        "titulo" => "Pack velocidad",
        "categoria" => "Carreras",
        "descuento" => 15,
        "descripcion" => "Promoción limitada en videojuegos de conducción."
    ],
    [
        "id" => 3,
        "titulo" => "Aventura épica",
        "categoria" => "Aventura",
        "descuento" => 25,
        "descripcion" => "Rebaja en títulos de exploración y aventura."
    ],
    [
        "id" => 4,
        "titulo" => "Construye tu mundo",
        "categoria" => "Construcción",
        "descuento" => 10,
        "descripcion" => "Oferta para juegos creativos y de construcción."
    ],
    [
        "id" => 5,
        "titulo" => "Rol clásico",
        "categoria" => "Rol",
        "descuento" => 30,
        "descripcion" => "Promoción especial en RPG clásicos."
    ]
];

$categoria = $_GET["categoria"] ?? "";

if ($categoria !== "") {
    $ofertasFiltradas = [];

    foreach ($ofertas as $oferta) {
        if ($oferta["categoria"] === $categoria) {
            $ofertasFiltradas[] = $oferta;
        }
    }

    $ofertas = $ofertasFiltradas;
}

echo json_encode($ofertas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);