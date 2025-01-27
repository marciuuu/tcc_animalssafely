<?php

namespace Etec\Animalssafely\Model;

class Mensagens {
    private BancoDeDados $bd; // Objeto da classe BancoDeDados
    private string $table = "Mensagens";

    public $id;
    public $id_usuario;
    public $id_instituicao;
    public $remetente;
    public $mensagem;
    public $lida;
    public $timestamp;

    // Construtor: inicializa o objeto BancoDeDados
    public function __construct(BancoDeDados $bd) {
        $this->bd = $bd;
    }

    // Método para criar uma nova mensagem
    public function criar() {
        $sql = "INSERT INTO " . $this->table . " (id_usuario, id_instituicao, remetente, mensagem, lida) ";
        $sql .= "VALUES ('{$this->id_usuario}', '{$this->id_instituicao}', '{$this->remetente}', '{$this->mensagem}', 0)";

        return $this->bd->executaSql($sql);
    }

    // Método para marcar uma mensagem como lida
    public function marcarComoLida(int $id_mensagem) {
        $sql = "UPDATE " . $this->table . " SET lida = 1 WHERE id = '{$id_mensagem}'";
        return $this->bd->executaSql($sql);
    }

    // Método para obter todas as mensagens entre um usuário e uma instituição
    public function obterMensagens(int $id_usuario, int $id_instituicao) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_usuario = '{$id_usuario}' AND id_instituicao = '{$id_instituicao}' ";
        $sql .= "ORDER BY timestamp ASC";

        $this->bd->executaSql($sql);
        return $this->bd->lerResultado(Mensagem::class);
    }

    // Método para obter todas as mensagens não lidas de um usuário ou instituição
    public function obterMensagensNaoLidas(int $id_usuario, int $id_instituicao, string $remetente) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_usuario = '{$id_usuario}' AND id_instituicao = '{$id_instituicao}' ";
        $sql .= "AND remetente = '{$remetente}' AND lida = 0";

        $this->bd->executaSql($sql);
        return $this->bd->lerResultado(Mensagem::class);
    }
}