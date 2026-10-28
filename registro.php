<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cuenta.html");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";

if (strlen($nombre) < 2 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($contrasena) < 8) {
    $mensaje = "Datos no válidos. Revisa nombre, correo y que la contraseña tenga 8 caracteres mínimo.";
} else {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
    if (!$stmt) {
        $mensaje = "Error prepare: " . $conexion->error;
    } else {
        $stmt->bind_param("sss", $nombre, $correo, $hash);
        if ($stmt->execute()) {
            $mensaje = "¡Cuenta creada con éxito, " . $nombre . "! Ahora puedes iniciar sesión.";
        } else {
            if ($conexion->errno == 1062) {
                $mensaje = "Ese correo ya está registrado.";
            } else {
                $mensaje = "Error MySQL: " . $conexion->error;
            }
        }
        $stmt->close();
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Registro | e.l.f. Beauty</title><link rel="stylesheet" href="estilos.css"></head>
<body>
<header class="encabezado"><a class="logo" href="index.html">e.l.f. <span>BEAUTY</span></a></header>
<main style="max-width:600px;margin:60px auto;text-align:center;padding:30px;background:#fffdf9">
<h1>Mi cuenta</h1>
<p style="font-size:18px"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
<p><a class="boton" href="cuenta.html">Volver a Mi cuenta</a> <a class="boton" href="index.html" style="margin-left:10px">Ir al inicio</a></p>
</main>
</body></html>
