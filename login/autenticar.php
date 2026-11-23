<?php
session_start();
require '../includes/conexao.php';

$login = $_POST['login'] ?? '';
$senha = $_POST['senha'] ?? '';

// Busca só pelo login (a senha é conferida depois, com password_verify)
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE login = :login AND ativo = 'S'");
$stmt->bindValue(':login', $login);
$stmt->execute();
$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'   => $usuario['id_usuario'],
        'nome' => $usuario['nome']
    ];
    header('Location: ../index/dashboard.php');
    exit;
}

header('Location: index.php?erro=1');
exit;
