<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$termo = $_GET['termo'] ?? '';
if ($termo) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE ? OR cpf LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$termo%", "%$termo%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY id DESC");
}
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Clientes</title>
</head>
<body>
    <h2>Lista de Clientes</h2>
    
    <form method="GET">
        <input type="text" name="termo" placeholder="Buscar por nome ou CPF..." value="<?php echo htmlspecialchars($termo); ?>">
        <button type="submit">Buscar</button>
    </form>
    <br>
    
    <a href="novo_cliente.php">+ Novo Cliente</a> | <a href="index.php">Voltar ao Menu</a>
    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente['id']; ?></td>
                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cpf']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td>
                        <a href="atualizar_cliente.php?id=<?php echo $cliente['id']; ?>">Editar</a> | 
                        <a href="apagar_cliente.php?id=<?php echo $cliente['id']; ?>" 
                           onclick="return confirm('Tem certeza que deseja apagar este cliente?');" 
                           style="color: red;">Apagar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>