<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }

// Controle de Expiração de Sessão (15 minutos)
if (isset($_SESSION['tempo_login']) && (time() - $_SESSION['tempo_login'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: login.php?erro=expirado");
    exit;
}
$_SESSION['tempo_login'] = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Dashboard - Locadora</title></head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
    
    <nav>
        <ul>
            <li><a href="clientes.php">Gerir Clientes</a></li>
            <li><a href="veiculos.php">Gerir Veículos</a></li>
            <li><a href="locacoes.php">Gerir Locações</a></li>
            <li><a href="logout.php">Sair do Sistema</a></li>
        </ul>
    </nav>
</body>
</html>