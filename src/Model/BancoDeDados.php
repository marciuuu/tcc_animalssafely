<?php

namespace Etec\Animalssafely\Model;

class BancoDeDados
{
    private \PDO $conexao;
    private \PDOStatement $resultado;
    private String $lastInsertedId;

    public function abrirConexao(string $usuario, string $senha, string $url): bool
    {

        try {
            $configuracao = array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"
            );

            $this->conexao = new \PDO($url, $usuario, $senha, $configuracao);
            return true;
        } catch (\Exception $erro) {
            echo "Falha ao conectar no banco de dados " . $erro->getMessage();
            exit(0);
        }

        return false;
    }

    public function getConexao(): ?\PDO {
        return $this->conexao;
    }

    public function getLastInsertId(): int {
        return $this->lastInsertedId;
    }

    public function fecharConexao(): bool
    {
        unset($this->conexao);
        return true;
    }

    public function lerResultado(string $classe)
    {
        return $this->resultado->fetchAll(\PDO::FETCH_CLASS, $classe);
    }


    public function executaSql(string $sql): bool
    {
        try {
            $this->conexao->beginTransaction();
            $this->resultado = $this->conexao->prepare($sql);
            $this->resultado->execute();          
            $this->lastInsertedId = $this->conexao->lastInsertId();
            $this->conexao->commit();
            return true;
        } catch (\Exception $ex) {
            echo "Falha ao executar o sql: " . $ex->getMessage();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function executaSql2(string $sql)
{
    $this->conexao->beginTransaction();
    
    $this->resultado = $this->conexao->prepare($sql);
    $this->resultado->execute();
    
    // Para SELECT, retorna os dados
    $dados = $this->resultado->fetchAll(\PDO::FETCH_ASSOC);
    
    $this->conexao->commit();
    
    return $dados; // Retorna os dados obtidos
}

    public function getErro() {
        return $this->conexao->errorInfo();
    }

}
