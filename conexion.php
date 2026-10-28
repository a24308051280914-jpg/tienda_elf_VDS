<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "tienda_elf";

$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("No se pudo conectar con la base de datos. Revisa que MySQL esté iniciado en XAMPP.");
}

$conexion->set_charset("utf8mb4");
?>
