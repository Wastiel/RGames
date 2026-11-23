<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: ../index/dashboard.php');
    exit;
}
require '../includes/header.php';
?>

<div class="login-container">
    <div class="card login-card shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-center titulo-pagina mb-4"><i class="bi bi-controller"></i> RGames</h3>

            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-danger py-2">Usuário ou senha inválidos.</div>
            <?php endif; ?>

            <form action="autenticar.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuário</label>
                    <input type="text" name="login" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-rgames w-100">Entrar</button>
            </form>
            <p class="text-muted text-center small mt-3 mb-0">Teste: admin / password</p>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
