<?php
namespace Etec\Animalssafely\Model;

class Adocao{
    public int $codAdocao;
    public string $dataAdocao;
    public ?int $codAnimal_fk;
    public ?int $codUsuario_fk;

    public function salvar(): bool {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "INSERT INTO adocao (dataAdocao, codAnimal_fk, codUsuario_fk) VALUES (:dataAdocao, :codAnimal_fk, :codUsuario_fk)";
        $stmt = $bd->getConexao()->prepare($sql);
    
        // Verifique os valores antes de executar
        var_dump($this->dataAdocao, $this->codAnimal_fk, $this->codUsuario_fk);
    
        $stmt->bindParam(':dataAdocao', $this->dataAdocao);
        $stmt->bindParam(':codAnimal_fk', $this->codAnimal_fk, \PDO::PARAM_INT);
        $stmt->bindParam(':codUsuario_fk', $this->codUsuario_fk, \PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    public function carregar(int $codAnimal_fk): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from adocao ";
        $sql .= " where codAnimal_fk = {$codAnimal_fk}";

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

    public function exibirTodos()
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM animal";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }

    public function exibir($codAnimal_fk)
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT codUsuario_fk FROM adocao WHERE codAnimal_fk = :codAnimal_fk";
        $stmt = $bd->getConexao()->prepare($sql);
        $stmt->bindParam(':codAnimal_fk', $codAnimal_fk, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retorna todos os dados encontrados
    }

    
    public function buscarPorId(int $codAnimal_fk): ?self
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT * FROM adocao WHERE codAnimal_fk = {$codAnimal_fk}";

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