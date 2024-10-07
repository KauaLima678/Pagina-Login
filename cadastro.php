<?php

if(isset($_SESSION['usuario_id'])){

  header("location: dashboard.php");
  exit();

}

require_once 'includes/config.php';

$mensagem_sucesso = "";
$mensagem_erro = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);


  $sql_verifica = "SELECT * FROM usuarios WHERE email = ?";
  $stmt_verifica = $conn->prepare($sql_verifica);
  $stmt_verifica->bind_param('s', $email);
  $stmt_verifica->execute();
  $resultado = $stmt_verifica->get_result();


  if ($resultado->num_rows > 0) {
    $mensagem_erro = "Este email ja esta cadastrado.";
  } else {
    $sql = "INSERT INTO usuarios (nome, email, senha) Values (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nome, $email, $senha);


    if ($stmt->execute()) {
      $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso";
      header("Location: cadastro.php");
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
  <link rel="stylesheet" href="./css/index.css">
</head>


<body>
  <section class="login">
  <div>
    <form action="" method="POST">
      <h2>Cadastro de <br>usuarios</h2>
      <input type="text" id="nome" name="nome"  placeholder="Digite seu Nome" required><br>
      <input type="text" id="email" name="email" placeholder="Digite seu email" required><br>
      <input type="password" id="senha" name="senha"  placeholder="Digite sua senha" required><br>

      
      <?php
      if($mensagem_sucesso):?>
      <p> <?php echo $mensagem_sucesso; ?> </p>     
      <?php endif; ?>

      <?php
      if($mensagem_erro):?>
      <p> <?php echo $mensagem_erro; ?> </p>     
      <?php endif; ?>

      <input type="submit" value="Cadastrar" class="button">
      <p>Ja tem conta ? <br><a class="cadastro" href="index.php">Ir para login</a></p>
    </form>
  </div>
  </section>
  
</body>


</html>