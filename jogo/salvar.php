<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$id             = $_POST['id'] ?? '';
$titulo         = trim($_POST['titulo'] ?? '');
$id_plataforma  = $_POST['id_plataforma'] ?? '';
$id_tipo_jogo   = $_POST['id_tipo_jogo'] ?? '';
$tempo_jogado   = (float)($_POST['tempo_jogado'] ?? 0);
$valor_original = (float)($_POST['valor_original'] ?? 0);
$valor_pago     = (float)($_POST['valor_pago'] ?? 0);
$dt_compra      = empty($_POST['dt_compra']) ? null : $_POST['dt_compra'];   // vazio -> NULL
$completou      = ($_POST['completou'] ?? 'N') === 'S' ? 'S' : 'N';
$observacao     = trim($_POST['observacao'] ?? '');

// Nota é opcional: vazio vira NULL; fora de 1 a 5 também
$nota = ($_POST['nota'] ?? '') === '' ? null : (int)$_POST['nota'];
if ($nota !== null && ($nota < 1 || $nota > 5)) {
    $nota = null;
}

// Validação no servidor
if ($titulo === '' || $id_plataforma === '' || $id_tipo_jogo === ''
    || $tempo_jogado < 0 || $valor_original < 0 || $valor_pago < 0) {
    header('Location: form.php?erro=1' . ($id !== '' ? '&id=' . urlencode($id) : ''));
    exit;
}

if (empty($id)) {
    $sql = "INSERT INTO jogo
                (titulo, id_plataforma, id_tipo_jogo, tempo_jogado, valor_original,
                 valor_pago, dt_compra, completou, nota, observacao, ativo)
            VALUES
                (:titulo, :id_plataforma, :id_tipo_jogo, :tempo_jogado, :valor_original,
                 :valor_pago, :dt_compra, :completou, :nota, :observacao, 'S')";
} else {
    $sql = "UPDATE jogo
            SET titulo = :titulo,
                id_plataforma = :id_plataforma,
                id_tipo_jogo = :id_tipo_jogo,
                tempo_jogado = :tempo_jogado,
                valor_original = :valor_original,
                valor_pago = :valor_pago,
                dt_compra = :dt_compra,
                completou = :completou,
                nota = :nota,
                observacao = :observacao
            WHERE id_jogo = :id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':titulo', $titulo);
$stmt->bindValue(':id_plataforma', $id_plataforma);
$stmt->bindValue(':id_tipo_jogo', $id_tipo_jogo);
$stmt->bindValue(':tempo_jogado', $tempo_jogado);
$stmt->bindValue(':valor_original', $valor_original);
$stmt->bindValue(':valor_pago', $valor_pago);
$stmt->bindValue(':dt_compra', $dt_compra);
$stmt->bindValue(':completou', $completou);
$stmt->bindValue(':nota', $nota, $nota === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':observacao', $observacao);

if (!empty($id)) {
    $stmt->bindValue(':id', $id);
}

$stmt->execute();

header('Location: listar.php');
exit;
