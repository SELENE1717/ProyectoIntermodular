<?php
$host = 'localhost';
$db   = 'TravelWay';
$user = 'postgres';     
$pass = '1234567'; 
$port = '5433';          

try {
  $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}
?>
