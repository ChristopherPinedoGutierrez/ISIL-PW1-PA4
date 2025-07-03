<?php
require_once 'conexion.php';

// Obtener todos los usuarios
$sql = "SELECT id, clave FROM usuarios";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll();

foreach ($usuarios as $usuario) {
    $id = $usuario['id'];
    $clave_plana = $usuario['clave'];

    // Evita encriptar claves que ya están encriptadas
    if (password_get_info($clave_plana)['algo']) {
        continue; // Ya está encriptada, no la toques
    }

    // Encriptar la clave
    $clave_encriptada = password_hash($clave_plana, PASSWORD_DEFAULT);

    // Actualizar en la base de datos
    $update = $pdo->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
    $update->execute([$clave_encriptada, $id]);

    echo "Usuario $id: clave encriptada ✅<br>";
}
?>
