<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$itens = $pdo->query("
    SELECT id_tipo_jogo, nome, ativo
    FROM tipo_jogo
    ORDER BY nome
")->fetchAll();

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="titulo-pagina mb-0">Tipos de Jogo</h3>
        <a href="form.php" class="btn btn-rgames"><i class="bi bi-plus-lg"></i> Novo</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Nome</th><th>Situação</th><th class="text-end">Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= h($item['id_tipo_jogo']) ?></td>
                            <td><?= h($item['nome']) ?></td>
                            <td>
                                <?php if ($item['ativo'] == 'S'): ?>
                                    <span class="badge badge-ativo">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-inativo">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="form.php?id=<?= (int)$item['id_tipo_jogo'] ?>" class="btn btn-sm btn-rgames-warning">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <a href="toggleAtivo.php?id=<?= (int)$item['id_tipo_jogo'] ?>" class="btn btn-sm btn-outline-secondary">
                                    <?= $item['ativo'] == 'S' ? 'Desativar' : 'Ativar' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$itens): ?>
                        <tr><td colspan="4" class="text-center text-muted">Nenhum registro.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
