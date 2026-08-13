<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
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

    public function listarCandidatos(): array {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM usuario WHERE isCandidato = true ORDER BY nome ASC'
            );
            $stmt->execute([]);
            $lista = [];
            foreach ($stmt->fetchAll() as $dados) {
                $lista[] = new Usuario($dados);
            }
            return $lista;
        } catch(Exception $e) {
            echo 'Erro ao buscar candidatos: ' . $e->getMessage();
            return [];
        }
    }

    public function criarUsuario(string $nome, string $cpf, string $foto, int $isCandidato, int $votos, DateTime $ultimoVoto): void {
        $stmt = $this->pdo->prepare('INSERT INTO usuario (nome, cpf, foto, isCandidato, votos, ultimoVoto) VALUES (:nome, :cpf, :foto, :isCandidato, :votos, :ultimoVoto)');
        $stmt->execute([':nome' => $nome, ':cpf' => $cpf,':foto' => $foto,
        ':isCandidato' => $isCandidato,
        ':votos' => $votos,
        ':ultimoVoto' => $ultimoVoto->format('Y-m-d H:i:s')]);
    }

    public function atualizarVotos(int $id, int $votos): void {
        $stmt = $this->pdo->prepare('UPDATE usuario SET votos = :votos WHERE id = :id');
        $stmt->execute([':votos' => $votos,':id' => $id]);
    }

    public function atualizarUltimoVoto(int $id, DateTime $ultimoVoto): void {
        $stmt = $this->pdo->prepare('UPDATE usuario SET ultimoVoto = :ultimoVoto WHERE id = :id');
        $stmt->execute([':ultimoVoto' => $ultimoVoto->format('Y-m-d H:i:s'), ':id' => $id]);
    }

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
