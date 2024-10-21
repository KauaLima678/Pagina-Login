<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php"); // Corrigido
    exit();
}

require_once 'includes/config.php';

$mensagem_sucesso = "";
$mensagem_erro = "";
$produto = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_verifica = "SELECT * FROM produtos WHERE id = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param('i', $id); // Corrigido para usar $id
    $stmt_verifica->execute();
    $resultado = $stmt_verifica->get_result();
    if ($resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
    } else {
        $mensagem_erro = "Produto não encontrado!";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = intval($_POST['quantidade']);

    $sql_update = "UPDATE produtos SET nome=?, descricao=?, quantidade=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update); // Corrigido para usar $sql_update
    $stmt_update->bind_param('ssii', $nome, $descricao, $quantidade, $id); // Corrigido

    if ($stmt_update->execute()) {
        $mensagem_sucesso = "Produto atualizado com sucesso.";
        header("Location: dashboard.php");
        exit();
    } else {
        $mensagem_erro = "Erro ao atualizar o produto.";
    }

    $stmt_update->close(); // Corrigido
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
<section class="login">
    <div>
        <form action="" method="POST">
            <h2>Editar produto</h2>
            <input type="text" id="nome" name="nome" value="<?php echo $produto['nome'] ?? '' ?>" required><br>
            <input type="text" id="descricao" name="descricao" value="<?php echo $produto['descricao'] ?? '' ?>" required><br>
            <input type="number" id="quantidade" name="quantidade" value="<?php echo $produto['quantidade'] ?? '' ?>" required><br>
            <?php if ($mensagem_sucesso) : ?>
                <p><?php echo $mensagem_sucesso; ?></p>
            <?php endif; ?>
            <?php if ($mensagem_erro) : ?>
                <p><?php echo $mensagem_erro; ?></p>
            <?php endif; ?>
            <input type="submit" value="Atualizar" class="button">
        </form>
    </div>
</section>
</body>
</html>
