<?php

include("conexion.php");
include("funciones.php");

if (isset($_POST["id_usuario"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = '" . $_POST["id_usuario"] . "' LIMIT 1");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach ($resultado as $fila) {
        $salida["nombre"] = $fila["nombre"];
        $salida["apellido"] = $fila["apellido"];
        $salida["telefono"] = $fila["telefono"];
        $salida["email"] = $fila["email"];
        if ($fila["imagen"] != " ") {
            $salida["imagen_usuario"] = "<img src='img/" . $fila["imagen"] .  "' class='img_thumbnail' width='90' height='90' />
            <input type='hidden' name='imagen_usuario_oculta' value='" . $fila["imagen"] . "'/>
            ";
        } else {
            $salida["imagen_usuario"] = "<input type='hidden' name='imagen_usuario_oculta' value='" . $fila["imagen"] . "'/>";
        }
    }
}

echo json_encode($salida);
