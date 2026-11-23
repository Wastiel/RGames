<?php
// Dados de acesso ao banco (troque conforme o seu ambiente)
$host    = "mariadb";   // no Docker, use o nome do serviço (ex.: "mariadb")
$banco   = "RGames";
$usuario = "root";
$senha   = "mypass";            // no XAMPP a senha costuma ser vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
