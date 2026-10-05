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
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "<script>
                alert('Erro: Este cliente possui histórico de locações vinculadas e não pode ser apagado.');
                window.location.href = 'clientes.php';
              </script>";
        exit;
    }
}

header("Location: clientes.php");
exit;