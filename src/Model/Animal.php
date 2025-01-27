<?php
namespace Etec\Animalssafely\Model;

class Animal{
    public int $codAnimal;
    public string $nome;
    public string $sexo;
    public string $porte;
    public string $idade;
    public string $descricao;
    public bool $situacao;
    public ?int $codUsuario_fk;
    public ?int $codInstituicao_fk;
    public int $codEspecie_fk;
    public ?string $imagem;

    public function salvar(): bool
{
    $bd = new BancoDeDados();
    $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $imagemCaminho = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $nomeImagem = $_FILES['imagem']['name'];
            $caminhoDestino = 'Uploads/' . basename($nomeImagem);

            // Move a imagem para o diret�rio de destino
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoDestino)) {
                $imagemCaminho = $caminhoDestino;
            }
        }

    $sql = "insert into animal (nome, sexo, porte, idade, descricao, situacao, codUsuario_fk, codInstituicao_fk, codEspecie_fk, imagem) values ";
    $sql .= "('{$this->nome}','{$this->sexo}', '{$this->porte}', '{$this->idade}', '{$this->descricao}', '{$this->situacao}', '{$this->codUsuario_fk}', '{$this->codInstituicao_fk}', '{$this->codEspecie_fk}', '{$this->imagem}')";

    return $bd->executaSql($sql);
}

    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE animal SET nome = '{$this->nome}', sexo = '{$this->sexo}', porte = '{$this->porte}', idade = '{$this->idade}', descricao = '{$this->descricao}', situacao = '{$this->situacao}', imagem = '{$this->imagem}' WHERE codAnimal = {$this->codAnimal}";

        return $bd->executaSql($sql);
    }

    public function atualizarSituacao(): bool
{
    $bd = new BancoDeDados();
    $bd->abrirConexao(USUARIO, SENHA, URL_BD);

    $sql = "UPDATE animal SET situacao = '{$this->situacao}' WHERE codAnimal = {$this->codAnimal}";

    return $bd->executaSql($sql);
}

    public static function carregarTodos(): array
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from animal";

        if ($bd->executaSql($sql)) {
            return $bd->lerResultado(self::class);
        }

        return [];
    }

    public function carregar(int $codAnimal): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from animal ";
        $sql .= " where codAnimal = {$codAnimal}";

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

        $sql = "delete from animal where id = {$this->codAnimal}";

        return $bd->executaSql($sql);
    }

    public function exibirPorUsuario($v)
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM animal WHERE codUsuario_fk = {$v}";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }

    public function exibirPorInstituicao($v)
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM animal WHERE codInstituicao_fk = {$v}";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }

    public function exibirTodos()
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM animal";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }

    public function exibir()
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM animal WHERE situacao = 1";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }
    
    public function buscarPorId(int $codAnimal): ?self
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT * FROM animal WHERE codAnimal = {$codAnimal}";

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