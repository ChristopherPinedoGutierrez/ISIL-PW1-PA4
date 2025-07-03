<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$sql = "SELECT * FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll();

echo json_encode($productos);
?>
