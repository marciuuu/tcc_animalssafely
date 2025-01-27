<?php
namespace Etec\Animalssafely\Model;

class Raca{
    public int $codRaca;
    public string $descricao;

    public function salvar(): bool {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "INSERT INTO raca (descricao) VALUES ('{$this->descricao}')";

        // Tente executar a consulta
        try {
            if ($bd->executaSql($sql)) {
                $this->codRaca = $bd->getLastInsertId();
                return true;
            } else {
                throw new \Exception("Erro ao executar a consulta SQL.");
            }
        } catch (\PDOException $e) {
            // Log da exce��o de PDO
            throw new \Exception("Erro na inser��o no banco de dados: " . $e->getMessage());
        }
    }

    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE raca SET descricao = '{$this->descricao}' WHERE codRaca = {$this->codRaca}";

        return $bd->executaSql($sql);
    }


    public static function carregarTodos(): array
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from raca";

        if ($bd->executaSql($sql)) {
            return $bd->lerResultado(self::class);
        }

        return [];
    }

    public function carregar(int $codRaca): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from raca ";
        $sql .= " where codRaca = {$codRaca}";

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

        $sql = "delete from raca where id = {$this->codRaca}";

        return $bd->executaSql($sql);
    }

    public function buscarPorId(int $codRaca): ?self
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT * FROM raca WHERE codRaca = {$codRaca}";

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
