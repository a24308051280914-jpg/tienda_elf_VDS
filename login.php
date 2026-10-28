<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cuenta.html");
    exit;
}

$correo = trim($_POST["correo"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";

if (empty($correo) || empty($contrasena)) {
    $mensaje = "Falta correo o contraseña.";
} else {
    $stmt = $conexion->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?");
    if (!$stmt) {
        $mensaje = "Error prepare: " . $conexion->error;
    } else {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario && password_verify($contrasena, $usuario["contrasena"])) {
            session_start();
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $mensaje = "¡Hola, " . $usuario["nombre"] . "! Iniciaste sesión correctamente.";
        } else {
            $mensaje = "El correo o la contraseña no son correctos. Intentaste con: " . htmlspecialchars($correo);
        }
        $stmt->close();
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Login | e.l.f. Beauty</title><link rel="stylesheet" href="estilos.css"></head>
<body>
<header class="encabezado"><a class="logo" href="index.html">e.l.f. <span>BEAUTY</span></a></header>
<main style="max-width:600px;margin:60px auto;text-align:center;padding:30px;background:#fffdf9">
<h1>Mi cuenta</h1>
<p style="font-size:18px"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
<p><a class="boton" href="index.html">Ir al inicio</a> <a class="boton" href="cuenta.html" style="margin-left:10px">Volver</a></p>
</main>
</body></html>
