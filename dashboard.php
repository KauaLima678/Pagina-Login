<?php
session_start();
 
// Desconectar
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("location: index.php");
  exit();
}
 
// Verificar se existe usuário logado
if (!isset($_SESSION['usuario_id'])) {
  header("location: index.php");
  exit();
}
 
require_once 'includes/config.php'; // Inclua a conexão correta aqui
 
$sql = "SELECT * from produtos";
$resultado = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/dashboard.css">
    <title>DashBoard</title>
</head>

<body>
<header>
  <a href="cadastro-produtos.php">Cadastrar Produto</a>
    <a href="?logout=true">Sair</a>
  </header>
  
<main>
  <?php if ($resultado->num_rows > 0) : ?>
    <?php while ($produto = $resultado->fetch_assoc()) : ?>
      <div class="produto">
        <h3> <?php echo $produto['nome'] ?> </h3>
        <h3> Descrição: <?php echo $produto['descricao'] ?> </h3>
        <h3> Quantidade: <?php echo $produto['quantidade'] ?> </h3>
        <div class="buttons">
         <a href="excluir-produto.php?id=<?php echo $produto['id'] ?>">Excluir</a>
         <a href="editar-produto.php?id=<?php echo $produto['id'] ?>">Editar</a>
        </div>
      </div>
    <?php endwhile ?>
    <?php else: ?>
      <p>Nenhum produto cadastrado.</p>
    <?php endif; ?>
    <?php $conn->close(); ?>
</main>


</body>

</html>