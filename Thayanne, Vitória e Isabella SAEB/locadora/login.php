<?php
session_start();
require 'conexao.php';

$erro = '';
if (isset($_GET['erro']) && $_GET['erro'] == 'expirado') {
    $erro = "Sessão expirada por inatividade. Faça login novamente.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = hash('sha256', $_POST['senha']);

    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['tempo_login'] = time();
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou palavra-passe incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Login - Locadora</title></head>
<body>
    <h2>Login do Sistema</h2>
    <?php if($erro) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
        E-mail: <input type="email" name="email" required><br><br>
        Palavra-passe: <input type="password" name="senha" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>