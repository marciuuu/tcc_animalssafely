<?php
namespace Etec\Animalssafely\Model;

class Usuario{
    public int $codUsuario;
    public string $nome;
    public string $cpf;
    public string $cep;
    public string $telefone;
    public string $numero;
    public string $dataNasc;
    public string $email;
    public string $senha;
    public bool $situacao;

    public function salvar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "insert into usuario (nome, cpf, cep, numero, telefone, dataNasc, email, senha, situacao) values ";
        $sql = $sql . "('{$this->nome}','{$this->cpf}', '{$this->cep}', '{$this->numero}', '{$this->telefone}', '{$this->dataNasc}', '{$this->email}', '{$this->senha}', '{$this->situacao}')";

        return $bd->executaSql($sql);
    }

/*
    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "update usuario";
        $sql .= "set nome = '{$this->nome}', cpf = '{$this->cpf}', cep = '{$this->cep}', telefone = '{$this->telefone}', email = '{$this->email}', senha = '{$this->senha}', situacao = '{$this->situacao}' ";
        $sql .= " where codUsuario = {$this->codUsuario}";

        return $bd->executaSql($sql);
    }
*/

    public function atualizar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE usuario SET nome = '{$this->nome}', cep = '{$this->cep}', telefone = '{$this->telefone}', email = '{$this->email}' WHERE codUsuario = {$this->codUsuario}";

        return $bd->executaSql($sql);
    }

    public function atualizarSenha(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "UPDATE usuario SET senha = '{$this->senha}' WHERE codUsuario = {$this->codUsuario}";

        return $bd->executaSql($sql);
    }

    public function atualizarSituacao(): bool
{
    $bd = new BancoDeDados();
    $bd->abrirConexao(USUARIO, SENHA, URL_BD);

    $sql = "UPDATE usuario SET situacao = '{$this->situacao}' WHERE codUsuario = {$this->codUsuario}";

    return $bd->executaSql($sql);
}


    public static function carregarTodos(): array
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from usuario";

        if ($bd->executaSql($sql)) {
            return $bd->lerResultado(self::class);
        }

        return [];
    }

    public function carregar(int $codUsuario): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from usuario ";
        $sql .= " where codUsuario = {$codUsuario}";

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
/*
    public function apagar(): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "delete from usuario where id = {$this->codUsuario}";

        return $bd->executaSql($sql);
    }
*/

    public function carregarAutenticacao(string $email, string $senha): bool
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "select * from usuario ";
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

        $sql = "select * from usuario ";
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
    
        $sql = "SELECT * FROM usuario";
        return $bd->executaSql2($sql); // Agora retorna os dados
    }

    public function exibirCod($email)
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT codUsuario FROM usuario where email = {$email}";
        return $bd->executaSql($sql); // Agora retorna os dados
    }

    public function exibirNome($codUsuario)
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT nome FROM usuario where codUsuario = {$codUsuario}";
        return $bd->executaSql($sql); // Agora retorna os dados
    }

    public function obterCodUsuarioPorEmail($email) {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);
    
        $sql = "SELECT codUsuario FROM usuario WHERE email = :email";
        $stmt = $bd->getConexao()->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);  // Adicionando o namespace global
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC)["codUsuario"];
    }

    public function buscarPorId(int $codUsuario): ?self
    {
        $bd = new BancoDeDados();
        $bd->abrirConexao(USUARIO, SENHA, URL_BD);

        $sql = "SELECT * FROM usuario WHERE codUsuario = {$codUsuario}";

        if ($bd->executaSql($sql)) {
            $lista = $bd->lerResultado(self::class);

            if (count($lista) > 0) {
                // Retorna o primeiro resultado como objeto Animal
                return $lista[0];
            }
        }
    }
}