<?php
session_start();
 
require_once 'includes/config.php';
 
$mensagem_erro = "";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];
 
  $sql_verifica = "SELECT * FROM usuarios WHERE email = ?";
  $stmt_verifica = $conn->prepare($sql_verifica);
  $stmt_verifica->bind_param('s', $email);
  $stmt_verifica->execute();
  $resultado = $stmt_verifica->get_result();
  $usuario = $resultado->fetch_assoc();
 
  if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header("Location: dashboard.php");
    exit();
  } else {
    $mensagem_erro = "Email ou senha incorretos.";
  }
 
  $stmt_verifica->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <section class="login">
        <div class="card-login">
        <form action="" method="POST">
      <h2>Login</h2>
      <input type="text" id="nome" name="nome"  placeholder="Digite seu Nome" required><br>
      <input type="text" id="email" name="email" placeholder="Digite seu email" required><br>
      <input type="password" id="senha" name="senha"  placeholder="Digite sua senha" required><br>


      <?php
      if($mensagem_erro):?>
      <p class="mensagem"> <?php echo $mensagem_erro; ?> </p>     
      <?php endif; ?>

      <input class="button" type="submit" value="Login">
      <p>Não possui uma conta? <br>
      <a class="cadastro" href="cadastro.php"><span></span>Ir para cadastro</a></p>
    </form>
        </div>
    </section>
</body>
</html>