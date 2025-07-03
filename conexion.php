<?php
//Conexion:
$host = 'localhost';
$db = 'bodega_db';
$pass = '';
$user = 'root';
//Manejo de errores:
try {
    $pdo  = new PDO("mysql:host = $host;dbname=$db;charset=utf8", $user,$pass);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Conexion exitosa";    
} catch (PDOException $e) {
    die("Error en la conexion: " . $e ->getMessage());
}


?>