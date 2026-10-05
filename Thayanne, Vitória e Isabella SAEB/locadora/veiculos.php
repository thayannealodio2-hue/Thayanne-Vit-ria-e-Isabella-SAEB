<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$termo = $_GET['termo'] ?? '';
if ($termo) {
    $stmt = $pdo->prepare("SELECT * FROM veiculos WHERE modelo LIKE ? OR placa LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$termo%", "%$termo%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM veiculos ORDER BY id DESC");
}
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Veículos</title>
</head>
<body>
    <h2>Lista de Veículos</h2>
    
    <form method="GET">
        <input type="text" name="termo" placeholder="Buscar por modelo ou placa..." value="<?php echo htmlspecialchars($termo); ?>">
        <button type="submit">Buscar</button>
    </form>
    <br>

    <a href="novo_veiculo.php">+ Novo Veículo</a> | <a href="index.php">Voltar ao Menu</a>
    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Ano</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($veiculos) > 0): ?>
                <?php foreach ($veiculos as $veiculo): ?>
                <tr>
                    <td><?php echo $veiculo['id']; ?></td>
                    <td><?php echo htmlspecialchars($veiculo['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($veiculo['placa']); ?></td>
                    <td><?php echo htmlspecialchars($veiculo['ano'] ?? '-'); ?></td>
                    <td>
                        <a href="atualizar_veiculo.php?id=<?php echo $veiculo['id']; ?>">Editar</a> | 
                        <a href="apagar_veiculo.php?id=<?php echo $veiculo['id']; ?>" 
                           onclick="return confirm('Tem certeza que deseja apagar este veículo?');" 
                           style="color: red;">Apagar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum veículo encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
