<?php

session_start();
if(!isset($_SESSION['usuario_id'])){

  header("location: index.php");
  exit();

}

require_once 'includes/config.php';

$mensagem_sucesso = "";
$mensagem_erro = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $quantidade = intval($_POST['quantidade']);


  $sql_verifica = "SELECT * FROM produtos WHERE nome = ?";
  $stmt_verifica = $conn->prepare($sql_verifica);
  $stmt_verifica->bind_param('s', $nome);
  $stmt_verifica->execute();
  $resultado = $stmt_verifica->get_result();


  if ($resultado->num_rows > 0) {
    $mensagem_erro = "Este Produto ja esta cadastrado.";
  } else {
    $sql = "INSERT INTO produtos (nome, descricao, quantidade) Values (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nome, $descricao, $quantidade);


    if ($stmt->execute()) {
      $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso";
      header("Location: cadastro-produtos.php");
      exit();
    } else {
      $mensagem_erro = "Erro ao cadastrar" . $conn->error;
    }
  }

  $stmt->close();
  $conn->close();

}


?>


<!DOCTYPE html>
<html lang="pt-br">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="stylesheet" href="./css/cadastro-produtos.css">
</head>


<body>
  <section class="login">
  <div>
    <form action="" method="POST">
      <h2>Cadastro de <br>Produtos</h2>
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome"  placeholder="Digite seu Nome" required><br>
      <label for="descricao">Descrição</label>
      <input type="text" id="descricao" name="descricao" placeholder="Descreva seu produto" required><br>
      <label for="quantidade">Quantidade</label>
      <input type="number" id="quantidade" name="quantidade" required><br>

      
      <?php
      if($mensagem_sucesso):?>
      <p> <?php echo $mensagem_sucesso; ?> </p>     
      <?php endif; ?>

      <?php
      if($mensagem_erro):?>
      <p> <?php echo $mensagem_erro; ?> </p>     
      <?php endif; ?>

      <input type="submit" value="Cadastrar" class="button">
      <a href="dashboard.php">Ir para DashBoard</a>
    </form>
  </div>
  </section>
  
</body>


</html>