<?php

class Usuario {

    private int     $id;
    private string  $nome;
    private string  $cpf;
    private string  $foto;
    private int    $isCandidato;
    private int    $votou;

    public function __construct(array $dados) {
        $this->id               = (int)     ($dados['id']           ?? 0);
        $this->nome             = (string)  ($dados['nome']         ?? '');
        $this->cpf              = (string)  ($dados['cpf']          ?? '');
        $this->foto             = (string)  ($dados['foto']         ?? '');
        $this->isCandidato      = (int)     ($dados['isCandidato']  ?? 0);
        $this->votou            = (int)     ($dados['votou']  ?? 0);
    }

    public function getId():                int         { return $this->id; }
    public function getNome():              string      { return $this->nome; }
    public function getCpf():               string      { return $this->cpf; }
    public function getFoto():              string      { return $this->foto; }
    public function getIsCandidato():       int        { return $this->isCandidato; }
    public function getVotou():             int        { return $this->votou; }

    public static function novo(int $id, string $nome, string $cpf, string $foto, int $isCandidato, int $votou): Usuario {
        if ($id <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $usuario = new Usuario(['id' => $id]);
        $usuario->alterarDados($nome, $cpf, $foto, $isCandidato, $votou);

        return $usuario;
    }

    public function alterarDados($nome, $cpf, $foto, $isCandidato, $votou): void {

        $this->nome             = $nome;
        $this->cpf              = $cpf;
        $this->foto             = $foto;
        $this->isCandidato      = $isCandidato;
        $this->votou      = $votou;
    }

    public function registrarIdGerado(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }

        $this->id = $id;
    }
}
