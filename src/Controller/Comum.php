<?php

namespace Etec\Animalssafely\Controller;

use Etec\Animalssafely\Model\Usuario;
use Etec\Animalssafely\Model\BancoDeDados;

class Comum
{
    private \Twig\Environment $ambiente;
    private \Twig\Loader\FilesystemLoader $carregador;

    public function __construct()
    {
        $this->carregador = new \Twig\Loader\FilesystemLoader("./src/View");
        $this->ambiente = new \Twig\Environment($this->carregador);
    }
    
    public function animal($dados) : void{  
        session_start();

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("cadastro_animal.html", $dados);
        }
        else{
            header("Location: login");
        }
        
    }
    public function salvarAnimal($dados) : void {
        session_start();
    
        $animal = new \Etec\Animalssafely\Model\Animal();
        $especie = new \Etec\Animalssafely\Model\Especie();
        $raca = new \Etec\Animalssafely\Model\Raca();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
    
        $animal->nome = $dados['nome'] ?? '';
        $animal->sexo = $dados['sexo'] ?? ''; 
        $animal->porte = $dados['porte'] ?? ''; 
        $animal->idade = $dados['idade'] ?? '';
        $animal->descricao = $dados['descricao'] ?? '';
        $animal->situacao = true;
    
        $raca->descricao = $dados['raca'] ?? '';
    
        if (!$raca->salvar()) {
            echo "Erro ao salvar a raça.";
            exit();
        }
    
        if (!isset($raca->codRaca)) {
            echo "Erro: o código da raça não foi atribuído.";
            exit();
        }
        $especie->descricao = $dados['especie'] ?? '';
        $especie->codRaca_fk = $raca->codRaca;
    
        if (!$especie->salvar()) {
            echo "Erro ao salvar a espécie.";
            exit();
        }
    
        if (!isset($especie->codEspecie)) {
            echo "Erro: o código da espécie não foi atribuído.";
            exit();
        }
        $animal->codEspecie_fk = $especie->codEspecie;
    
        // Processar upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'Uploads/';
            $uploadFile = $uploadDir . basename($_FILES['imagem']['name']);
    
            // Verifica se a pasta de uploads existe, senão cria
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Move o arquivo para o diretório de uploads
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $animal->imagem = $uploadFile; // Salva o caminho da imagem no objeto Animal
            } else {
                echo "Erro ao salvar a imagem.";
                exit();
            }
        } else {
            // Salvar imagem padrão se nenhuma imagem for enviada
            $animal->imagem = 'Uploads/imagem_padrao.jpg'; // Caminho da imagem padrão
        }
    
        if ($usuario->verificaEmail($_SESSION['email'])) {
            $animal->codUsuario_fk = $_SESSION['codUsuario'];
            $animal->codInstituicao_fk = -1;
            $animal->salvar();
            
            header("Location: home_apos_login_usuario");
            exit();
        } else {
            $animal->codUsuario_fk = -1;
            $animal->codInstituicao_fk = $_SESSION['codInstituicao'];
            $animal->salvar();
            
            header("Location: home_apos_login_instituicao");
            exit();
        }
    }
    

    public function telaEditaAnimal($dados){
        $id = $dados["id"];
        session_start();

        $_SESSION['codAnimal'] = $id;

        $animal = new \Etec\Animalssafely\Model\Animal();
        $especie = new \Etec\Animalssafely\Model\Especie();
        $raca = new \Etec\Animalssafely\Model\Raca();

        $dadosAnimal = $animal->buscarPorId($id);
        $dadosEspecie = $especie->buscarPorId($id);
        $dadosRaca = $raca->buscarPorId($id);

        if ($dadosAnimal) {
            echo $this->ambiente->render("editar_animal.html", ["animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca]);
        } else {
            echo "Animal não encontrado!";
        }
    }

    public function editaAnimal($dados)
{
    session_start();
    $animal = new \Etec\Animalssafely\Model\Animal();
    $especie = new \Etec\Animalssafely\Model\Especie();
    $raca = new \Etec\Animalssafely\Model\Raca();
    $usuario = new \Etec\Animalssafely\Model\Usuario();
    $instituicao = new \Etec\Animalssafely\Model\Instituicao();

    var_dump($_SESSION['codAnimal']);
    //$animal->carregar($_SESSION['codAnimal']);
    $animal->codAnimal = $_SESSION['codAnimal'];
    $especie->codEspecie = $animal->codAnimal;
    $raca->codRaca = $animal->codAnimal;
    $animal->nome = $dados['nome'];
    $animal->sexo = $dados['sexo'];
    $animal->porte = $dados['porte'];
    $animal->idade = $dados['idade'];
    $animal->descricao = $dados['descricao'];
    $animal->situacao = true;

    // Caminho da imagem padrão
    $imagemPadrão = 'Uploads/imagem_padrao.jpg';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'Uploads/';
        $uploadFile = $uploadDir . basename($_FILES['imagem']['name']);

        // Verifica se a pasta de uploads existe, senão cria
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
            $animal->imagem = $uploadFile; // Salva o caminho da imagem no objeto Animal
        } else {
            echo "Erro ao salvar a imagem.";
            exit();
        }
    } else {
        // Atribui a imagem padrão se não houver imagem enviada
        $animal->imagem = $imagemPadrão;
    }

    $especie->descricao = $dados['especie'];
    $raca->descricao = $dados['raca'];

    // Verificação da existência do código da espécie e da raça
    if (!isset($especie->codEspecie) || !isset($raca->codRaca)) {
        echo "Erro: código da espécie ou da raça não foi atribuído.";
        exit();
    }

    $id = $animal->codAnimal;

    // Atualiza os dados do animal, espécie e raça
    if ($animal->atualizar() && $especie->atualizar() && $raca->atualizar()) {
        header("Location: info_meu_animal/{$id}");
    } else {
        echo "Erro ao atualizar o animal!";
    }
}


    public function adotarAnimal($dados){
        session_start();
        $id = $dados["id"];

        $_SESSION["animalAdotado"] = $id;

        $animal = new \Etec\Animalssafely\Model\Animal();
        $animal->codAnimal = intval($id);
        //$animal->situacao = false;

        //$animal->atualizarSituacao();
        echo $this->ambiente->render("registro_adocao.html");
    }

    public function registrarAdocao($dados){
        session_start();
        $adocao = new \Etec\Animalssafely\Model\Adocao();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $animal = new \Etec\Animalssafely\Model\Animal();

        $animal->carregar(intval($_SESSION['animalAdotado']));

        if(isset($animal->codAnimal)){
            $adocao->codAnimal_fk = $animal->codAnimal;
            $animal->situacao = false;
        }
        $adocao->dataAdocao = $dados['dataAdoc'];

        $codUsuario = $usuario->obterCodUsuarioPorEmail($dados['email']);
        
        if($codUsuario == false){
            die("Não existe um usuário com este email");
            return;
        }

        $adocao->codUsuario_fk = $codUsuario;   
        
        //var_dump($_SESSION);
        if($adocao->salvar()){
            if($animal->atualizarSituacao()){
                header('Location: meus_animais');
            }
        }
    }


    public function usuario($dados){
        echo $this->ambiente->render("cadastro_usuario.html");
    }
    public function salvarUsuario($dados) : void{
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();

        $usuario->nome = $dados['nome'] ?? '';
        $usuario->cpf = $dados['cpf'] ?? ''; 
        $usuario->cep = $dados['cep'] ?? ''; 
        $usuario->numero = $dados['numero'] ?? ''; 
        $usuario->telefone = preg_replace('/[^0-9]/', '', $dados['telefone'] ?? '');
        $usuario->dataNasc = $dados['dataNasc'] ?? '';
        $usuario->email = $dados['email'] ?? ''; 
        $usuario->senha = $dados['senha'] ?? '';

        $usuario->situacao = true;

        if ($usuario->verificaEmail($usuario->email)) {
            header("Location: cadastro_usuario");
            exit; 
        }

        if ($instituicao->verificaEmail($usuario->email)) {
            header("Location: cadastro_usuario");
            exit; 
        }

        $usuario->salvar();
        header("Location: login");
    }


    public function instituicao($dados){
        echo $this->ambiente->render("cadastro_instituicao.html");
    }
    public function salvarInstituicao($dados) : void{
        var_dump($dados);
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao->nome = $dados['nome'] ?? '';
        $instituicao->cnpj = $dados['cnpj'] ?? ''; 
        $instituicao->cep = $dados['cep'] ?? '';
        $instituicao->numero = $dados['numero'] ?? ''; 
        $instituicao->telefone = preg_replace('/[^0-9]/', '', $dados['telefone'] ?? '');
        $instituicao->email = $dados['email'] ?? ''; 
        $instituicao->senha = $dados['senha'] ?? ''; 
        $instituicao->situacao = true;

        if ($instituicao->verificaEmail($instituicao->email)) {
            header("Location: cadastro_instituicao");
            exit; 
        }

        if ($usuario->verificaEmail($instituicao->email)) {
            header("Location: cadastro_instituicao");
            exit; 
        }

        $instituicao->salvar();
        header("location: login");
    }

    public function logar($dados) : void {
        session_start();
    
        @$email = $dados["email"];
        @$senha = $dados["senha"];
    
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
    
        if ($email && $senha) {
            if ($usuario->carregarAutenticacao($email, $senha)) {
                if (isset($usuario->situacao) && $usuario->situacao) {
                    $_SESSION["codUsuario"] = $usuario->codUsuario;
                    $_SESSION["nome"] = $usuario->nome;
                    $_SESSION["email"] = $usuario->email;
                    $_SESSION["cep"] = $usuario->cep;
                    $_SESSION["telefone"] = $usuario->telefone;
                    header("Location: home_apos_login_usuario");
                    exit();
                }
            }
            if ($instituicao->carregarAutenticacao($email, $senha)) {
                if (isset($instituicao->situacao) && $instituicao->situacao) {
                    $_SESSION["codInstituicao"] = $instituicao->codInstituicao;
                    $_SESSION["nome"] = $instituicao->nome;
                    $_SESSION["email"] = $instituicao->email;
                    $_SESSION["cep"] = $instituicao->cep;
                    $_SESSION["telefone"] = $instituicao->telefone;
                    header("Location: home_apos_login_instituicao");
                    exit();
                }
            }
        }
        
        echo $this->ambiente->render("login.html", array("mensagem" => "Usuário não encontrado ou credenciais inválidas"));
    }

    public function logout($dados){
        session_start();
        session_unset();
        session_destroy();
        header("location: index");
    }

    public function autoExcluir($dados){
        session_start();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();

        if(isset($_SESSION['codUsuario'])){
            $usuario->codUsuario = $_SESSION['codUsuario'];
            $usuario->situacao = false;
            $usuario->atualizarSituacao();
            session_unset();
            session_destroy();
            header("location: login");
        }
        else if(isset($_SESSION['codInstituicao'])){
            $instituicao->codInstituicao = $_SESSION['codInstituicao'];
            $instituicao->situacao = false;
            $instituicao->atualizarSituacao();
            session_unset();
            session_destroy();
            header("location: login");
        }
        
    }

    public function excluirUsuario($dados){
        $id = $dados["id"];
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $usuario->codUsuario = intval($id);
        $usuario->situacao = false;

        $usuario->atualizarSituacao();
        header("Location: ../home_apos_login_usuario");
    }

    public function excluirInstituicao($dados){
        $id = $dados["id"];
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
        $instituicao->codInstituicao = intval($id);
        $instituicao->situacao = false;

        $instituicao->atualizarSituacao();
        header("Location: ../home_apos_login_usuario");
    }

    public function excluirAnimal($dados){
        $id = $dados["id"];
        $animal = new \Etec\Animalssafely\Model\Animal();
        $animal->codAnimal = intval($id);
        $animal->situacao = false;

        $animal->atualizarSituacao();
        header("Location: ../home_apos_login_usuario");
    }

    public function atualizarDados($dados){
        session_start();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();

        

        @$senha = $dados["senha"];
        @$novaSenha = $dados["novaSenha"];
        @$novaSenhaCon = $dados["novaSenhaCon"];

        if($usuario->verificaEmail($_SESSION['email'])){
            $usuario->carregar($_SESSION['codUsuario']);
        
            $usuario->nome = $dados['nome'];
            $usuario->cep = $dados['cep'];
            $usuario->telefone = $dados['telefone'];
            $usuario->email = $dados['email'];
            
            $_SESSION['nome'] = $usuario->nome;
            $_SESSION['cep'] = $usuario->cep;
            $_SESSION['telefone'] = $usuario->telefone;
            $_SESSION['email'] = $usuario->email;

            if(strlen($senha) > 0 && strlen($novaSenha) > 0 && strlen($novaSenhaCon) > 0){
                if($senha == $usuario->senha){
                    if($novaSenha == $novaSenhaCon){
                        $usuario->senha = $novaSenha;
                        $usuario->atualizarSenha();
                    }
                }
            }
    
            $usuario->atualizar();
    
            header("location: home_apos_login_usuario");
            exit();
        } else{
            $instituicao->carregar($_SESSION['codInstituicao']); 
        
            $instituicao->nome = $dados['nome'];
            $instituicao->cep = $dados['cep'];
            $instituicao->telefone = $dados['telefone'];
            $instituicao->email = $dados['email'];
            
            $_SESSION['nome'] = $instituicao->nome;
            $_SESSION['cep'] = $instituicao->cep;
            $_SESSION['telefone'] = $instituicao->telefone;
            $_SESSION['email'] = $instituicao->email;

            if(strlen($senha) > 0 && strlen($novaSenha) > 0 && strlen($novaSenhaCon) > 0){
                if($senha == $instituicao->senha){
                    if($novaSenha == $novaSenhaCon){
                        $instituicao->senha = $novaSenha;
                        $instituicao->atualizarSenha();
                    }
                }
            }
    
            $instituicao->atualizar();
    
            header("location: home_apos_login_instituicao");
            exit();
        }
        
    }
    
    public function index($dados){
        session_start();

        if(isset($_SESSION['codUsuario'])){
            header("Location: home_apos_login_usuario");
        }
        else if(isset($_SESSION['codInstituicao'])){
            header("Location: home_apos_login_instituicao");
        }
        else{
            echo $this->ambiente->render("index.html");   
        }
        
    }

    public function sobreNos($dados){
        session_start();

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("sobre_nos_logado.html", array("mensagem" => $_SESSION['nome']));
        }else{
            echo $this->ambiente->render("sobre_nos.html");
        }
        
    }

    public function ongs($dados){
        session_start();

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("ongs_logado.html", array("mensagem" => $_SESSION['nome']));
        }else{
            echo $this->ambiente->render("ongs.html");
        }
        
    }

    public function parcerias($dados){
        session_start();

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("parcerias_logado.html", array("mensagem" => $_SESSION['nome']));
        }else{
            echo $this->ambiente->render("parcerias.html");
        }
        
    }

    public function loading_pre($dados){
        echo $this->ambiente->render("loading.html");
    }

    public function preLogin($dados){
        echo $this->ambiente->render("pre_login.html");
    }

    public function login($dados){
        echo $this->ambiente->render("login.html");
    }

    public function home_apos_login_usuario($dados){
        session_start();

        if(isset($_SESSION['codUsuario'])){
            echo $this->ambiente->render("home_apos_login_usuario.html", array("mensagem" => $_SESSION['nome']));
        }
        else{
            header("Location: index");
        }
    }

    public function home_apos_login_instituicao($dados){
        session_start();

        if(isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("home_apos_login_instituicao.html", array("mensagem" => $_SESSION['nome']));
        }
        else{
            header("Location: index");
        }
    }

    public function perfil($dados){
        session_start();
        if(isset($_SESSION['codUsuario'])){
            echo $this->ambiente->render("perfil_usuario.html", array("nome" => $_SESSION["nome"], "cep" => $_SESSION["cep"], "telefone" => $_SESSION["telefone"], "email" => $_SESSION["email"]));
        }
        else if(isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("perfil_instituicao.html", array("nome" => $_SESSION["nome"], "cep" => $_SESSION["cep"], "telefone" => $_SESSION["telefone"], "email" => $_SESSION["email"]));
        }
    }

    public function lista($dados){
        session_start();
        $animal = new \Etec\Animalssafely\Model\Animal();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
        

        if($_SESSION['email'] == "Lucas_oba@outlook.com"){
            $dadosUsuario = $usuario->exibirTodos();
            $dadosInstituicao = $instituicao->exibirTodos();
            $dadosAnimal = $animal->exibirTodos();

            echo $this->ambiente->render("lista.html", array("usuario" => $dadosUsuario, "instituicao" => $dadosInstituicao, "animal" => $dadosAnimal));
        } else{
            header("Location: index");

        }
        
        
    }

    public function queroAdotar($dados){
        session_start();
        $animal = new \Etec\Animalssafely\Model\Animal();
        $usuario = new \Etec\Animalssafely\Model\Usuario();

        $result = $animal->exibir();

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            echo $this->ambiente->render("quero_adotar.html", array("lista" => $result, "mensagem" => $_SESSION['nome']));
        }
        else{
            header("Location: login");
        }
        
    }

    public function saibaMaisAnimal($dados){
        session_start();
        $id = $dados['id'];
        $animal = new \Etec\Animalssafely\Model\Animal();
        $especie = new \Etec\Animalssafely\Model\Especie();
        $raca = new \Etec\Animalssafely\Model\Raca();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
        $adocao = new \Etec\Animalssafely\Model\Adocao();

        $animal->carregar(intval($id));

        $dadosAnimal = $animal->buscarPorId($id);
        $dadosEspecie = $especie->buscarPorId($id);
        $dadosRaca = $raca->buscarPorId($id);

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            if($animal->codInstituicao_fk == -1){
                $usuario->carregar(intval($animal->codUsuario_fk));
                $tel = $usuario->telefone;
                $nomeUsuario = $usuario->nome;
                if($animal->codUsuario_fk == $_SESSION['codUsuario']){
                    echo $this->ambiente->render("saiba_mais_meu_animal.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "mensagem" => $_SESSION['nome']));
                }
                else{
                    echo $this->ambiente->render("saiba_mais.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "tel" => $tel, "tipo" => "Usuario - ", "nome" => $nomeUsuario, "mensagem" => $_SESSION['nome']));
                }
    
            }
            else{
                $instituicao->carregar(intval($animal->codInstituicao_fk));
                $tel = $instituicao->telefone;
                $nomeInstituicao = $instituicao->nome;
                if($animal->codInstituicao_fk == $_SESSION['codInstituicao']){
                    echo $this->ambiente->render("saiba_mais_meu_animal.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "mensagem" => $_SESSION['nome']));
                }
                else{
                    echo $this->ambiente->render("saiba_mais.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "tel" => $tel, "tipo" => "Instituicao - ", "nome" => $nomeInstituicao, "mensagem" => $_SESSION['nome']));
                }
            }  
        }
        else{
            header('Location: ../index');
        }
    }

    public function comunicar($dados){
        $tel = $dados['tel'];
        header("Location: https://wa.me/{$tel}");
    }

    public function meusAnimais($dados){
        session_start();
        $animal = new \Etec\Animalssafely\Model\Animal();
        $usuario = new \Etec\Animalssafely\Model\Usuario();

        if(isset($_SESSION['codUsuario'])){
            $result = $animal->exibirPorUsuario($_SESSION['codUsuario']);
        
            echo $this->ambiente->render("meus_animais.html", array("lista" => $result, "mensagem" => $_SESSION['nome']));
        }
        else if(isset($_SESSION['codInstituicao'])){
            $result = $animal->exibirPorInstituicao($_SESSION['codInstituicao']);
        
            echo $this->ambiente->render("meus_animais.html", array("lista" => $result, "mensagem" => $_SESSION['nome']));
        }
        else{
            header("Location: index");
        }
    }

    public function infoMeuAnimal($dados){
        session_start();
        $id = $dados['id'];
        $animal = new \Etec\Animalssafely\Model\Animal();
        $especie = new \Etec\Animalssafely\Model\Especie();
        $raca = new \Etec\Animalssafely\Model\Raca();
        $usuario = new \Etec\Animalssafely\Model\Usuario();
        $instituicao = new \Etec\Animalssafely\Model\Instituicao();
        $adocao = new \Etec\Animalssafely\Model\Adocao();

        $animal->carregar(intval($id));

        $dadosAnimal = $animal->buscarPorId($id);
        $dadosEspecie = $especie->buscarPorId($id);
        $dadosRaca = $raca->buscarPorId($id);
        $dadosAdocao = $adocao->carregar($id);
        

        if(isset($_SESSION['codUsuario']) || isset($_SESSION['codInstituicao'])){
            if ($dadosAnimal) {
                if($animal->situacao == 0){
                    $nomeAdotante = $usuario->buscarPorId($adocao->codUsuario_fk);
                    echo $this->ambiente->render("info_meu_animal_adotado.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "adotante" => $nomeAdotante, "mensagem" => $_SESSION['nome']));
                }
                else{
                    echo $this->ambiente->render("info_meu_animal.html", array("animal" => $dadosAnimal, "especie" => $dadosEspecie, "raca" => $dadosRaca, "mensagem" => $_SESSION['nome']));
                }
            } else {
                echo "Animal não encontrado!";
            }
        }
        else{
            header("Location: ../index");
        }
    }
}
