<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

// Valores "vazios" para o caso de cadastro novo
$jogo = [
    'id_jogo'        => '',
    'titulo'         => '',
    'id_plataforma'  => '',
    'id_tipo_jogo'   => '',
    'tempo_jogado'   => '0',
    'valor_original' => '0',
    'valor_pago'     => '0',
    'dt_compra'      => date('Y-m-d'),
    'completou'      => 'N',
    'nota'           => '',
    'observacao'     => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM jogo WHERE id_jogo = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $jogo = $stmt->fetch();

    if (!$jogo) {
        header('Location: listar.php');
        exit;
    }
}

// Listas suspensas: só itens ativos (mais o que já estava salvo, se for edição)
$stmt = $pdo->prepare("
    SELECT id_plataforma, nome FROM plataforma
    WHERE ativo = 'S' OR id_plataforma = :atual ORDER BY nome
");
$stmt->bindValue(':atual', (int)$jogo['id_plataforma']);
$stmt->execute();
$plataformas = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT id_tipo_jogo, nome FROM tipo_jogo
    WHERE ativo = 'S' OR id_tipo_jogo = :atual ORDER BY nome
");
$stmt->bindValue(':atual', (int)$jogo['id_tipo_jogo']);
$stmt->execute();
$tipos = $stmt->fetchAll();

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4" style="max-width: 800px;">
    <h3 class="titulo-pagina mb-3"><?= $id ? 'Editar Jogo' : 'Novo Jogo' ?></h3>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger py-2">
            Preencha título, plataforma e tipo. Tempo e valores não podem ser negativos.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= h($jogo['id_jogo']) ?>">

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" maxlength="120"
                               value="<?= h($jogo['titulo']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Plataforma</label>
                        <select name="id_plataforma" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($plataformas as $p): ?>
                                <option value="<?= (int)$p['id_plataforma'] ?>"
                                    <?= $p['id_plataforma'] == $jogo['id_plataforma'] ? 'selected' : '' ?>>
                                    <?= h($p['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de jogo</label>
                        <select name="id_tipo_jogo" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($tipos as $t): ?>
                                <option value="<?= (int)$t['id_tipo_jogo'] ?>"
                                    <?= $t['id_tipo_jogo'] == $jogo['id_tipo_jogo'] ? 'selected' : '' ?>>
                                    <?= h($t['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tempo jogado (horas)</label>
                        <input type="number" name="tempo_jogado" class="form-control" step="0.1" min="0"
                               value="<?= h($jogo['tempo_jogado']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor original (R$)</label>
                        <input type="number" name="valor_original" class="form-control" step="0.01" min="0"
                               value="<?= h($jogo['valor_original']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valor pago na promoção (R$)</label>
                        <input type="number" name="valor_pago" class="form-control" step="0.01" min="0"
                               value="<?= h($jogo['valor_pago']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Data da compra</label>
                        <input type="date" name="dt_compra" class="form-control"
                               value="<?= h($jogo['dt_compra']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Completou o jogo?</label>
                        <select name="completou" class="form-select">
                            <option value="N" <?= $jogo['completou'] == 'N' ? 'selected' : '' ?>>Não</option>
                            <option value="S" <?= $jogo['completou'] == 'S' ? 'selected' : '' ?>>Sim</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nota</label>
                        <select name="nota" class="form-select">
                            <option value="">Sem nota</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= $jogo['nota'] == $i ? 'selected' : '' ?>>
                                    <?= str_repeat('★', $i) ?> (<?= $i ?>)
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observações</label>
                        <textarea name="observacao" class="form-control" rows="3"><?= h($jogo['observacao']) ?></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-rgames">Salvar</button>
                    <a href="listar.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
