<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT l.*, c.nome AS cliente_nome, v.modelo AS veiculo_modelo, v.placa AS veiculo_placa 
        FROM locacoes l 
        LEFT JOIN clientes c ON l.cliente_id = c.id 
        LEFT JOIN veiculos v ON l.veiculo_id = v.id 
        ORDER BY l.id DESC";
$stmt = $pdo->query($sql);
$locacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Locações</title>
</head>
<body>
    <h2>Lista de Locações</h2>

    <a href="nova_locacao.php">+ Nova Locação</a> | <a href="index.php">Voltar ao Menu</a>
    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($locacoes) > 0): ?>
                <?php foreach ($locacoes as $locacao): ?>
                <tr>
                    <td><?php echo $locacao['id']; ?></td>
                    <td><?php echo htmlspecialchars($locacao['cliente_nome'] ?? 'Cliente Removido'); ?></td>
                    <td><?php echo htmlspecialchars(($locacao['veiculo_modelo'] ?? 'Veículo Removido') . ' (' . ($locacao['veiculo_placa'] ?? '') . ')'); ?></td>
                    <td><?php echo htmlspecialchars($locacao['data_inicio'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($locacao['data_fim'] ?? '-'); ?></td>
                    <td>
                        <a href="apagar_locacao.php?id=<?php echo $locacao['id']; ?>" 
                           onclick="return confirm('Tem certeza que deseja apagar este registro de locação?');" 
                           style="color: red;">Apagar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhuma locação cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
