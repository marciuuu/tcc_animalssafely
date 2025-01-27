<?php
namespace Etec\Animalssafely\Controller;

use Etec\Aula02\Model\Funcionario;

class Autenticacao{
    private \Twig\Environment $ambiente;
    private \Twig\Loader\FilesystemLoader $carregador;

    public function __construct()
    {
        $this->carregador = new \Twig\Loader\FilesystemLoader("./src/View");
        $this->ambiente = new \Twig\Environment($this->carregador);
    }

    public function salvar($dados) : void{
        $f = new \Etec\animalssafely\Model\Funcionario();

        $f->nome = $dados["nome"];
        $f->sobrenome = $dados["sobrenome"];
        $f->login = $dados["login"];
        $f->senha = $dados["senha"];

        if($f->salvar()){
            $this->ambiente->render("login.html", array("mensagem" => "Login criado"));
        }
        else{
            $this->ambiente->render("login.html", array("mensagem" => "Falha ao criar"));
            
        }
    }

    public function autenticar($dados) : void {
        session_start(); //Inicia os coockies

        if(isset($_SESSION["id"])){
            header("location: /");
            exit();
        }

        @$login = $dados["login"];
        @$senha = $dados["senha"];

        $f = new \Etec\animalssafely\Model\Funcionario();
        
        if(!is_null($login) && !is_null($senha) && $f->carregarAutenticacao($login, $senha)){
           
            $_SESSION["id"] = $f->id;
            $_SESSION["nome"] = $f->nome;

            header("location: /"); //redireciona pra qualquer pagina do site
        }
        else{
            echo $this->ambiente->render("login.html", array("mensagem" => "Falha ao autenticar")); 
        }

    }
}
