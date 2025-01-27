<?php
namespace Etec\Animalssafely\Model;

class Instituicao{
    public int $codInstituicao;
    public string $nome;
    public string $cnpj;
    public string $cep;
    public string $numero;
    public string $telefone;
    public string $email;
    public string $senha;
    public bool $situacao;

    public function salvar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "insert into instituicao (nome, cnpj, cep, numero, telefone, email, senha, situacao) values ";
        $sql = $sql . "('{$this->nome}','{$this->cnpj}', '{$this->cep}', '{$this->numero}', '{$this->telefone}', '{$this->email}', '{$this->senha}', '{$this->situacao}')";

        return $bd->executaSql($sql);
    }

/*
    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "update instituicao";
        $sql .= "set nome = '{$this->nome}', cnpj = '{$this->cnpj}', cep = '{$this->cep}', telefone = '{$this->telefone}', email = '{$this->email}', senha = '{$this->senha}', situacao = '{$this->situacao}'";
        $sql .= " where codInstituicao = {$this->codInstituicao}";

        return $bd->executaSql($sql);
    }
*/

    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE instituicao SET nome = '{$this->nome}', cep = '{$this->cep}', telefone = '{$this->telefone}', email = '{$this->email}' WHERE codInstituicao = {$this->codInstituicao}";

        return $bd->executaSql($sql);
    }

    public function atualizarSenha(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE instituicao SET senha = '{$this->senha}' WHERE codInstituicao = {$this->codInstituicao}";

        return $bd->executaSql($sql);
    }

    public function atualizarSituacao(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "UPDATE instituicao SET situacao = '{$this->situacao}' WHERE codInstituicao = {$this->codInstituicao}";
    
        return $bd->executaSql($sql);
    }

    public static function carregarTodos(): array
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from instituicao";

        if ($bd->executaSql($sql)) {
            return $bd->lerResultado(self::class);
        }

        return [];
    }

    public function carregar(int $codInstituicao): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from instituicao ";
        $sql .= " where codInstituicao = {$codInstituicao}";

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

        $sql = "delete from instituicao where id = {$this->codInstituicao}";

        return $bd->executaSql($sql);
    }

    public function carregarAutenticacao(string $email, string $senha): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from instituicao ";
        $sql .= " where email = '{$email}' and senha = '{$senha}'";

        if ($bd->executaSql($sql)) {
            $lista = $bd->lerResultado(self::class);

            if (count($lista) > 0) {
                foreach ($lista[0] as $propriedade => $valor) {
            
                    $this->$propriedade = is_null($valor) ? "" : $valor;
                }
            }
            return true;
        }
        return false;
    }

    public function verificaEmail(string $email): bool{
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from instituicao ";
        $sql .= " where email = '{$email}'";

        if ($bd->executaSql($sql)) {
            $lista = $bd->lerResultado(self::class);
            return count($lista) > 0;
        }
        return false;
    }

    public function exibirTodos()
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT * FROM instituicao";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }
}