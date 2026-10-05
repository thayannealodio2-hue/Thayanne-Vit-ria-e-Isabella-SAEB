<?php
$host = '127.0.0.1'; 
$port = '3307';$dbname = 'locadora_db';
$user = 'root';$pass = ''; 

try {
  
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8", $user, $pass);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4");
    $pdo->exec("USE `$dbname`");
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>