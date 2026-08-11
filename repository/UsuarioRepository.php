<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorCpf(string $cpf): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE cpf = :cpf LIMIT 1');
        $stmt->execute([':cpf' => $cpf]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
    }

    public function criarUsuario(string $nome, string $cpf, string $foto, int $isCandidato, int $votou): void {
        $stmt = $this->pdo->prepare('INSERT INTO usuario (nome, cpf, foto, isCandidato, votou) VALUES (:nome, :cpf, :foto, :votou)');
        $stmt->execute([':nome' => $nome, ':cpf' => $cpf,':foto' => $foto, ':isCandidato' => $isCandidato, ':votou' => $votou]);
    }

    // public function atualizarSenha(int $id, string $senha): void {
    //     $stmt = $this->pdo->prepare('UPDATE usuario SET senha = :senha WHERE id = :id');
    //     $stmt->execute([':senha' => $senha,':id' => $id]);
    // }

    // public function atualizarAvatar(int $id, string $foto_perfil): void {
    //     $stmt = $this->pdo->prepare('UPDATE usuario SET foto_perfil = :foto_perfil WHERE id = :id');
    //     $stmt->execute([':foto_perfil' => $foto_perfil,':id' => $id]);
    // }

    // public function atualizarEmail(int $id, string $email): void {
    //     $stmt = $this->pdo->prepare('UPDATE usuario SET email = :email WHERE id = :id');
    //     $stmt->execute([':email' => $email,':id' => $id]);
    // }

    // public function atualizarCorPrincipal(int $id, string $cor): void {
    //     $stmt = $this->pdo->prepare('UPDATE usuario SET cor_principal = :cor_principal WHERE id = :id');
    //     $stmt->execute([':cor_principal' => $cor,':id' => $id]);
    // }

    // public function atualizarCorSecundaria(int $id, string $cor): void {
    //     $stmt = $this->pdo->prepare('UPDATE usuario SET cor_secundaria = :cor_secundaria WHERE id = :id');
    //     $stmt->execute([':cor_secundaria' => $cor,':id' => $id]);
    // }

    public function excluir(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
