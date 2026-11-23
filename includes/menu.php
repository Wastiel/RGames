<nav class="navbar navbar-expand-lg navbar-dark navbar-rgames">
    <div class="container">
        <a class="navbar-brand" href="../index/dashboard.php">
            <i class="bi bi-controller"></i> RGames
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="../index/dashboard.php">Painel</a></li>
                <li class="nav-item"><a class="nav-link" href="../jogo/listar.php">Jogos</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Cadastros</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../plataforma/listar.php">Plataformas</a></li>
                        <li><a class="dropdown-item" href="../tipoJogo/listar.php">Tipos de Jogo</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text me-3">
                <i class="bi bi-person-circle"></i> <?= h($_SESSION['usuario']['nome']) ?>
            </span>
            <a class="btn btn-sm btn-outline-light" href="../login/logout.php">Sair</a>
        </div>
    </div>
</nav>
