<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$id   = $_POST['id'] ?? '';
$nome = trim($_POST['nome'] ?? '');

if ($nome === '') {             // validação no servidor (o "required" do HTML pode ser burlado)
    header('Location: form.php?erro=1' . ($id !== '' ? '&id=' . urlencode($id) : ''));
    exit;
}

if (empty($id)) {
    $sql = "INSERT INTO plataforma (nome, ativo) VALUES (:nome, 'S')";
} else {
    $sql = "UPDATE plataforma SET nome = :nome WHERE id_plataforma = :id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':nome', $nome);

if (!empty($id)) {
    $stmt->bindValue(':id', $id);
}

$stmt->execute();

header('Location: listar.php');
exit;
