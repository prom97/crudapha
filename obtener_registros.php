<?php

include("conexion.php");
include("funciones.php");

$query = "";
$salida = array();
$query = "SELECT * FROM usuarios ";

// buscar por nombre o apellido
// search = como se llama el campo en datatable
if (isset($_POST["search"]["value"])) {
    $query .= "WHERE nombre LIKE '%" . $_POST["search"]["value"] . "%'";
    $query .= "OR apellido LIKE '%" . $_POST["search"]["value"] . "%'";
}

// si tratan de ordenar la tabla
if (isset($_POST["order"])) {
    $query .= "ORDER BY" . $_POST["order"]["0"]["column"] . " " . $_POST["order"]["0"]["dir"] . " ";
} else {
    $query .= "ORDER BY id DESC ";
}

if ($_POST["length"] != -1) {
    $query .= "LIMIT " . $_POST["start"] . "," . $_POST["length"];
}

$stmt = $conexion->prepare($query);
$stmt->execute();
$resultado = $stmt->fetchAll();
$datos = array();
$filtered_rows = $stmt->rowCount();
foreach ($resultado as $fila) {
    $imagen = "";
    if ($fila["imagen"] != " ") {
        $imagen = "<img src='img/" . $fila["imagen"] .  "' class='img_thumbnail' width='50' height='50' />";
    }

    $sub_array = array();
    $sub_array[] = $fila["id"];
    $sub_array[] = $fila["nombre"];
    $sub_array[] = $fila["apellido"];
    $sub_array[] = $fila["telefono"];
    $sub_array[] = $fila["email"];
    $sub_array[] = $imagen;
    $sub_array[] = $fila["fecha_creacion"];
    $sub_array[] = "<button type='button' name='editar' id='" . $fila["id"] . "' class='btn btn-warning btn-xs editar'><i class='bi bi-pencil-square'></i></button>";
    $sub_array[] = "<button type='button' name='borrar' id='" . $fila["id"] . "' class='btn btn-danger btn-xs borrar'><i class='bi bi-trash3'></i></button>";
    $datos[] = $sub_array;
}

$salida = array(
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $filtered_rows,
    "recordsFiltered" => obtener_todos_registros(),
    "data" => $datos
);

echo json_encode($salida);
