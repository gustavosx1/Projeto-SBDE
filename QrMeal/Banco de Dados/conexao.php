<?php
$host = 'localhost';
$dbname = 'mydb';
$username = 'root'; // Altere para o usuário do seu banco de dados
$password = ''; // Altere para a senha do seu banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>