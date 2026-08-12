<?php

session_start();

// Se já estiver logado, vai direto para a página principal
if (!empty($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../repository/UsuarioRepository.php';

$erro = '';
$cpfFormulario = $_POST['cpf'] ?? '';
$nome = $_POST['nome'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cpf = trim($_POST['cpf'] ?? '');

  if ($cpf === '') {
    $erro = 'Insira seu CPF.';
  } 
  else {
    $repo = new UsuarioRepository();
    $usuario = $repo->buscarPorCPF($cpf);

    if($usuario === null){
      $repo->criarUsuario($nome, $cpfFormulario, '', 0, 0);
      $usuario = $repo->buscarPorCPF($cpfFormulario);
    }

    $_SESSION['id_usuario']     = $usuario->getId();
    $_SESSION['nome']           = $nome;
    $_SESSION['cpf']            = trim($cpfFormulario);
    $_SESSION['foto']           = $usuario->getFoto();
    $_SESSION['isCandidato']    = $usuario->getIsCandidato();
    $_SESSION['votou']          = $usuario->getVotou();

    header('Location: index.php');
    exit;
  }
}


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Urna Eletrônica</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="login-body">

<div class="login-card">
  <div class="login-logo">Urna Eletrônica</div>
  <h1 class="login-title">Entrar no sistema</h1>

  <?php if ($erro !== ''): ?>
    <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">

    <div class="form-group">
      <label for="nome">Seu nome:</label>
      <input
        type="text"
        id="nome"
        name="nome"
        placeholder="José"
        value="<?= htmlspecialchars($nome) ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="cpf">CPF</label>
      <input
        type="text"
        id="cpf"
        name="cpf"
        placeholder="123-456-789.67"
        value="<?= htmlspecialchars($cpfFormulario) ?>"
        required
      />
    </div>

    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
    <br>
    <br>
    <a href="cadastro.php" class="btn btn-secondary btn-full">Criar uma conta</a>
  </form>

</div>

</body>
</html>
