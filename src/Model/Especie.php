<?php
namespace Etec\Animalssafely\Model;

class Especie{
    public int $codEspecie;
    public string $descricao;
    public int $codRaca_fk;

    public function salvar(): bool {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "INSERT INTO especie (descricao, codRaca_fk) VALUES ('{$this->descricao}', '{$this->codRaca_fk}')";
        
        // Executa a query
        if ($bd->executaSql($sql)) {
            // Recupera o �ltimo ID inserido
            $this->codEspecie = $bd->getLastInsertId(); // Certifique-se de que esta fun��o est� implementada
            return true;
        }
        
        return false; // Se a inser��o falhar
    }

    public function exibir($v){
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT descricao FROM especie WHERE codEspecie = {$v}";
        return $bd->executaSql($sql); // Agora retorna os dados
    }

    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE especie SET descricao = '{$this->descricao}' WHERE codEspecie = {$this->codEspecie}";

        return $bd->executaSql($sql);

    }
    
    public static function carregarTodos(): array
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from especie";

        if ($bd->executaSql($sql)) {
            return $bd->lerResultado(self::class);
        }

        return [];
    }

    public function carregar(int $codEspecie): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from especie ";
        $sql .= " where codEspecie = {$codEspecie}";

        if ($bd->executaSql($sql)) {
            $lista = $bd->lerResultado(self::class);

            if (count($lista) > 0) {
                foreach ($lista[0] as $propriedade => $valor) {
                    $this->$propriedade = $valor;
                }
            }
            return true;
        }
        return false;
    }

    public function apagar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "delete from raca where id = {$this->codEspecie}";

        return $bd->executaSql($sql);
    }

    public function buscarPorId(int $codEspecie): ?self
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT * FROM especie WHERE codEspecie = {$codEspecie}";

        if ($bd->executaSql($sql)) {
            $lista = $bd->lerResultado(self::class);

            if (count($lista) > 0) {
                // Retorna o primeiro resultado como objeto Animal
                return $lista[0];
            }
        }

        // Se n�o encontrar nenhum animal, retorna null
        return null;
    }
}
