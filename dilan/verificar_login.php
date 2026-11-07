<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (!$usuario || !$contrasena) {
    header('Location: login.html?error=1');
    exit;
}

// Buscar usuario en la base de datos
$stmt = $conexion->prepare("SELECT id, contrasena_hash, rol FROM usuarios WHERE nombre_usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header('Location: login.html?error=1');
    exit;
}

$stmt->bind_result($id, $hash_guardado, $rol);
$stmt->fetch();
$stmt->close();

// Verificar contraseña con el hash
if (password_verify($contrasena, $hash_guardado)) {
    // Guardar datos en sesión
    $_SESSION['usuario_id'] = $id;
    $_SESSION['nombre_usuario'] = $usuario;
    $_SESSION['rol'] = $rol;
    
    // ✅ SIEMPRE te manda a index.html
    header('Location: index.php');
    exit;
} else {
    header('Location: login.html?error=1');
    exit;
}
?>
