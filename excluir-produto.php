<?php
require_once 'includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $sql = "DELETE FROM produtos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Produto excluído com sucesso!";
    } else {
        echo "Erro ao excluir o produto: " . $conn->error;
    }
    $conn->close();
    header("Location: dashboard.php");
} else {
    echo "ID do produto não encontrado!";
}
