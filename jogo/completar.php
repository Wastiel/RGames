<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Marca como completo (S) ou volta para em andamento (N)
    $stmt = $pdo->prepare("
        UPDATE jogo
        SET completou = IF(completou = 'S', 'N', 'S')
        WHERE id_jogo = :id
    ");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');
exit;
