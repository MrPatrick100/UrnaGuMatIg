<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';

?>

<?php
$repo = new UsuarioRepository();
$erro = '';

$cpf = 0;
if (isset($_SESSION['cpf'])) {
  $cpf = (string) $_SESSION['cpf'];
}

$usuario = null;
if ($cpf > 0) {
  $usuario = $repo->buscarPorCpf($cpf);
}

$candidatos = $repo->listarCandidatos();
$candidato = $candidatos[0];

$tipos    = ['Passiva', 'Ativa'];
$estilos  = ['Física', 'Mágica', 'Híbrida'];

$danos = ['1', '1d4', '1d6', '1d8', '1d10', '1d12', '1d20'];
$buffs = ['1[perícia]', '1d4[perícia]', '1[status]', '1d4[status]', '1[geral]', '1d4[geral]'];
$nerfs = ['1[perícia]', '1d4[perícia]', '1[status]', '1d4[status]', '1[geral]', '1d4[geral]'];

$alcances = ['Pessoal', 'Curto', 'Médio', 'Longo'];
$areas    = ['Individual', 'Reta', 'Cone', 'Raio'];
$duracoes = ['1', '1d4'];

$dataAtual  = new DateTime();
$ultimoVoto = $usuario->getUltimoVoto();
$idCandidato = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $idCandidato = $_POST['id-candidato'];
  $candidato = $repo->buscarPorId($idCandidato);

  $dataAtualEmSegundos = $dataAtual->getTimestamp();
  $ultimoVotoEmSegundos = $ultimoVoto->getTimestamp();
  $tempoDesdeUltimoVoto = $dataAtualEmSegundos - $ultimoVotoEmSegundos;
  $cooldownDoVoto = 10;

  if($tempoDesdeUltimoVoto >= $cooldownDoVoto){
    $repo->atualizarVotos($candidato->getId(), $candidato->getVotos() + 1);
    $repo->atualizarUltimoVoto($usuario->getId(), new DateTime());
  }
  else{
    $tempoRestante = $cooldownDoVoto - $tempoDesdeUltimoVoto;

    $horas = floor($tempoRestante / 3600);
    $minutos = floor(($tempoRestante % 3600) / 60);

    echo "Você já votou! Espere {$horas} horas e {$minutos} minutos para votar novamente :)";
}
  
  

// $repo->atualizarVotou($_SESSION['id_usuario'], true)
//   $nome       = trim  ($_POST['nome']             ?? '');
//   $tipo       = trim  ($_POST['tipo']             ?? '');
//   $ciclo      = (int) ($_POST['ciclo']            ?? 0);
//   $estilo     = trim  ($_POST['estilo']           ?? '');
//   $custo      = (int) ($_POST['custo']            ?? 0);
//   $dano       = trim  ($_POST['dano_completo']    ?? '');
//   $buff       = trim  ($_POST['buff_completo']    ?? '');
//   $nerf       = trim  ($_POST['nerf_completo']    ?? '');
//   $alcance    = trim  ($_POST['alcance']          ?? '');
//   $area       = trim  ($_POST['area']             ?? '');
//   $duracao    = trim  ($_POST['duracao_completa'] ?? '');
//   $pontos     = (int) ($_POST['pontos']           ?? 0);
//   $descricao  = trim  ($_POST['descricao']        ?? '');

//   $id_usuario = $_SESSION['id_usuario'];

//   if($_POST['acao'] === 'cadastrar') {
//     try {
//       $habilidade = Habilidade::novo($id_usuario, $nome, $tipo, $ciclo, $estilo, $custo, $dano, $buff, $nerf, $alcance, $area, $duracao, $pontos, $descricao, 0);
//       $repo_habilidade->salvar($habilidade);
//       $id_habilidade = $habilidade->getId();

//       $personagensSelecionados = $_POST['personagens'] ?? [];

//       foreach($personagensSelecionados as $id_personagem) {
//         $relacao = RelacaoPersonagemHabilidade::novo($id_usuario, (int)$id_personagem, $id_habilidade);
//         $repo_relacao->salvar($relacao);
//       }

//       header('Location: index2.php');
//       exit;
//     } 
//     catch (Exception $e) {
//       $erro = 'Ocorreu um erro ao cadastrar a habilidade: ' . $e->getMessage();
//     }
//   }
}
?>

<link rel="stylesheet" href="../assets/styleIndex.css">

<div class="page-header">
  <h2>Urna Eletrônica</h2>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="index.php" enctype="multipart/form-data">

    <div class="form-group">
      <label for="cpf">Seu CPF:<?= htmlspecialchars($usuario->getCpf()) ?></label>
    </div>

    <?php foreach ($candidatos as $cand): ?>

        <div class="form-group">
            <label for="nome">Nome:<?= htmlspecialchars($cand->getNome()) ?></label>
            <img src="../assets/img/blackout.png" class="img">
            <label for="cpf-candidato">CPF:<?= htmlspecialchars($cand->getCpf()) ?></label>
            <label for="votos-candidato">Votos:<?= htmlspecialchars($cand->getVotos()) ?></label>
        </div>

    <?php endforeach; ?>

    <div class="form-group">
      <label for="candidato">Candidato</label>
      <select id="id-candidato" name="id-candidato" required>
        <option value="">Selecione o candidato...</option>
        <?php foreach ($candidatos as $c): ?>
          <?php
            $selecionado = '';
            if ($candidato === $c) {
                $selecionado = 'selected';
            }
          ?>
          <option value="<?= $c->getId() ?>" <?= $selecionado ?>>
            <?= $c->getNome() ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Votar</button>
    </div>

  </form>
</div>