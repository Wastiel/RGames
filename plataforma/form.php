<?php
require '../includes/validarLogin.php';
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

$item = ['id_plataforma' => '', 'nome' => ''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM plataforma WHERE id_plataforma = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $item = $stmt->fetch();

    if (!$item) {               // id inexistente: volta para a lista
        header('Location: listar.php');
        exit;
    }
}

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4" style="max-width: 600px;">
    <h3 class="titulo-pagina mb-3"><?= $id ? 'Editar' : 'Novo' ?> — Plataforma</h3>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger py-2">Preencha o nome.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= h($item['id_plataforma']) ?>">

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" maxlength="60"
                           value="<?= h($item['nome']) ?>" required>
                </div>

                <button type="submit" class="btn btn-rgames">Salvar</button>
                <a href="listar.php" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
