<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("
        UPDATE jogo
        SET ativo = IF(ativo = 'S', 'N', 'S')
        WHERE id_jogo = :id
    ");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');
exit;
