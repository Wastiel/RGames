<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

// Filtros opcionais (vêm pela URL, método GET)
$busca   = trim($_GET['busca'] ?? '');
$filtro  = $_GET['completou'] ?? '';      // '', 'S' ou 'N'

$sql = "
    SELECT j.id_jogo, j.titulo, j.tempo_jogado, j.valor_original, j.valor_pago,
           (j.valor_original - j.valor_pago) AS economia,
           j.dt_compra, j.completou, j.nota, j.observacao, j.ativo,
           p.nome AS plataforma,
           t.nome AS tipo
    FROM jogo j
    INNER JOIN plataforma p ON p.id_plataforma = j.id_plataforma
    INNER JOIN tipo_jogo  t ON t.id_tipo_jogo  = j.id_tipo_jogo
    WHERE 1 = 1
";
$params = [];

if ($busca !== '') {
    $sql .= " AND j.titulo LIKE :busca";
    $params[':busca'] = '%' . $busca . '%';
}
if ($filtro === 'S' || $filtro === 'N') {
    $sql .= " AND j.completou = :completou";
    $params[':completou'] = $filtro;
}
$sql .= " ORDER BY j.titulo";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jogos = $stmt->fetchAll();

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="titulo-pagina mb-0"><i class="bi bi-joystick"></i> Meus Jogos</h3>
        <a href="form.php" class="btn btn-rgames"><i class="bi bi-plus-lg"></i> Novo Jogo</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por título..."
                   value="<?= h($busca) ?>">
        </div>
        <div class="col-md-3">
            <select name="completou" class="form-select">
                <option value="">Todos</option>
                <option value="S" <?= $filtro === 'S' ? 'selected' : '' ?>>Completados</option>
                <option value="N" <?= $filtro === 'N' ? 'selected' : '' ?>>Não completados</option>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Filtrar</button>
            <a href="listar.php" class="btn btn-link">Limpar</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Plataforma</th>
                        <th>Tipo</th>
                        <th>Tempo</th>
                        <th>Preço</th>
                        <th>Pago</th>
                        <th>Economia</th>
                        <th>Nota</th>
                        <th>Progresso</th>
                        <th>Situação</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jogos as $jogo): ?>
                        <tr>
                            <td>
                                <strong><?= h($jogo['titulo']) ?></strong>
                                <?php if ($jogo['dt_compra']): ?>
                                    <div class="small text-muted">Comprado em <?= date('d/m/Y', strtotime($jogo['dt_compra'])) ?></div>
                                <?php endif; ?>
                                <?php if ($jogo['observacao']): ?>
                                    <div class="small text-muted"><?= nl2br(h($jogo['observacao'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= h($jogo['plataforma']) ?></td>
                            <td><?= h($jogo['tipo']) ?></td>
                            <td><?= horas($jogo['tempo_jogado']) ?></td>
                            <td><?= moeda($jogo['valor_original']) ?></td>
                            <td><?= moeda($jogo['valor_pago']) ?></td>
                            <td class="<?= $jogo['economia'] > 0 ? 'text-success' : 'text-muted' ?>">
                                <?= moeda($jogo['economia']) ?>
                            </td>
                            <td class="estrelas">
                                <?php if ($jogo['nota']): ?>
                                    <?= str_repeat('★', (int)$jogo['nota']) . str_repeat('☆', 5 - (int)$jogo['nota']) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($jogo['completou'] == 'S'): ?>
                                    <span class="badge badge-completo"><i class="bi bi-trophy"></i> Completo</span>
                                <?php else: ?>
                                    <span class="badge badge-andamento">Em andamento</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($jogo['ativo'] == 'S'): ?>
                                    <span class="badge badge-ativo">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-inativo">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="form.php?id=<?= (int)$jogo['id_jogo'] ?>" class="btn btn-sm btn-rgames-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="completar.php?id=<?= (int)$jogo['id_jogo'] ?>" class="btn btn-sm btn-outline-primary"
                                   title="Marcar/desmarcar como completo">
                                    <i class="bi bi-trophy"></i>
                                </a>
                                <a href="toggleAtivo.php?id=<?= (int)$jogo['id_jogo'] ?>" class="btn btn-sm btn-outline-secondary"
                                   title="Ativar/desativar">
                                    <i class="bi bi-power"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$jogos): ?>
                        <tr><td colspan="11" class="text-center text-muted py-4">Nenhum jogo encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
