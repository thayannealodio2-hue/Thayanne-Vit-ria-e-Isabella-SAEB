<?php
session_start();
require_once 'conexao.php';

// Verificar autenticação e tempo limite de 15 minutos (900 segundos)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: login.php?msg=timeout");
    exit;
}
$_SESSION['last_activity'] = time();

$erro = '';

// Processar formulário ao submeter
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $veiculo_id = $_POST['veiculo_id'] ?? '';
    $data_retirada = $_POST['data_retirada'] ?? '';
    $data_devolucao = !empty($_POST['data_devolucao']) ? $_POST['data_devolucao'] : NULL;

    if (!empty($cliente_id) && !empty($veiculo_id) && !empty($data_retirada)) {
        $stmt = $pdo->prepare("INSERT INTO locacoes (cliente_id, veiculo_id, data_retirada, data_devolucao) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $veiculo_id, $data_retirada, $data_devolucao]);
        
        header("Location: locacoes.php");
        exit;
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Buscar clientes e veículos para preencher as seleções
$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$veiculos = $pdo->query("SELECT id, modelo, placa FROM veiculos ORDER BY modelo ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Locação</title>
</head>
<body>
    <h2>Nova Locação</h2>

    <?php if ($erro): ?>
        <p style="color: red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="POST" action="nova_locacao.php">
        <p>
            <label for="cliente_id">Cliente:</label><br>
            <select name="cliente_id" id="cliente_id" required>
                <option value="">-- Selecione o Cliente --</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo $cliente['id']; ?>">
                        <?php echo htmlspecialchars($cliente['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="veiculo_id">Veículo:</label><br>
            <select name="veiculo_id" id="veiculo_id" required>
                <option value="">-- Selecione o Veículo --</option>
                <?php foreach ($veiculos as $veiculo): ?>
                    <option value="<?php echo $veiculo['id']; ?>">
                        <?php echo htmlspecialchars($veiculo['modelo'] . ' - ' . $veiculo['placa']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="data_retirada">Data de Retirada:</label><br>
            <input type="date" name="data_retirada" id="data_retirada" required>
        </p>

        <p>
            <label for="data_devolucao">Data de Devolução (Opcional):</label><br>
            <input type="date" name="data_devolucao" id="data_devolucao">
        </p>

        <p>
            <button type="submit">Salvar</button>
            <a href="locacoes.php">Cancelar</a>
        </p>
    </form>
</body>
</html>
