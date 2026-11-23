<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Inverte o valor: 'S' vira 'N' e 'N' vira 'S' (exclusão lógica)
    $stmt = $pdo->prepare("
        UPDATE plataforma
        SET ativo = IF(ativo = 'S', 'N', 'S')
        WHERE id_plataforma = :id
    ");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');
exit;
