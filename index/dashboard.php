<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

// Uma única consulta com várias funções de agregação (COUNT, SUM)
$resumo = $pdo->query("
    SELECT
        COUNT(*)                                          AS total_jogos,
        COALESCE(SUM(completou = 'S'), 0)                 AS total_completos,
        COALESCE(SUM(tempo_jogado), 0)                    AS total_horas,
        COALESCE(SUM(valor_pago), 0)                      AS total_gasto,
        COALESCE(SUM(valor_original - valor_pago), 0)     AS total_economia
    FROM jogo
    WHERE ativo = 'S'
")->fetch();

$custoPorHora = $resumo['total_horas'] > 0
    ? $resumo['total_gasto'] / $resumo['total_horas']
    : 0;

$percCompletos = $resumo['total_jogos'] > 0
    ? round($resumo['total_completos'] * 100 / $resumo['total_jogos'])
    : 0;

// GROUP BY: um resumo por plataforma
$porPlataforma = $pdo->query("
    SELECT p.nome,
           COUNT(*)            AS qtd,
           SUM(j.tempo_jogado) AS horas,
           SUM(j.valor_pago)   AS gasto
    FROM jogo j
    INNER JOIN plataforma p ON p.id_plataforma = j.id_plataforma
    WHERE j.ativo = 'S'
    GROUP BY p.id_plataforma, p.nome
    ORDER BY qtd DESC
")->fetchAll();

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4">
    <h3 class="titulo-pagina mb-4"><i class="bi bi-speedometer2"></i> Painel</h3>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Jogos na biblioteca</div>
                <div class="numero"><?= (int)$resumo['total_jogos'] ?></div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Completados</div>
                <div class="numero"><?= (int)$resumo['total_completos'] ?> <small class="fs-6 text-muted">(<?= $percCompletos ?>%)</small></div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Horas jogadas</div>
                <div class="numero"><?= horas($resumo['total_horas']) ?></div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Custo por hora</div>
                <div class="numero"><?= moeda($custoPorHora) ?></div>
            </div></div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Total gasto</div>
                <div class="numero"><?= moeda($resumo['total_gasto']) ?></div>
            </div></div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card dashboard-card shadow-sm"><div class="card-body">
                <div class="text-muted small">Economizado em promoções</div>
                <div class="numero text-success"><?= moeda($resumo['total_economia']) ?></div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Por plataforma</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>Plataforma</th><th>Jogos</th><th>Horas</th><th>Gasto</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($porPlataforma as $p): ?>
                        <tr>
                            <td><?= h($p['nome']) ?></td>
                            <td><?= (int)$p['qtd'] ?></td>
                            <td><?= horas($p['horas']) ?></td>
                            <td><?= moeda($p['gasto']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$porPlataforma): ?>
                        <tr><td colspan="4" class="text-center text-muted">Nenhum jogo cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
