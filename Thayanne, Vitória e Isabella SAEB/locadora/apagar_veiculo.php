<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "<script>
                alert('Erro: Este veículo possui histórico de locações vinculadas e não pode ser apagado.');
                window.location.href = 'veiculos.php';
              </script>";
        exit;
    }
}

header("Location: veiculos.php");
exit;