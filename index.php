<?php


require_once "vendor/autoload.php";

// Cria o roteador
$roteador = new CoffeeCode\Router\Router(URL);

// Informa o diretorio onde os controladores se encontram
$roteador->namespace("\Etec\Animalssafely\Controller");

/*
*  Rota principal
*/
$roteador->group(null); // Aponta para a raíz do site

$roteador->get("/tela_edita_animal/{id}", "Comum:telaEditaAnimal");
$roteador->get("/adotar_animal/{id}", "Comum:adotarAnimal");
$roteador->get("/saiba_mais_animal/{id}", "Comum:saibaMaisAnimal");
$roteador->get("/comunicar/{tel}", "Comum:comunicar");
$roteador->get("/info_meu_animal/{id}", "Comum:infoMeuAnimal");
$roteador->get("/excluir_usuario/{id}", "Comum:excluirUsuario");
$roteador->get("/excluir_instituicao/{id}", "Comum:excluirInstituicao");
$roteador->get("/excluir_animal/{id}", "Comum:excluirAnimal");

$roteador->post("/edita_animal", "Comum:editaAnimal");

$roteador->get("/index", "Comum:index");
$roteador->get("/sobre_nos", "Comum:sobreNos");
$roteador->get("/ongs", "Comum:ongs");
$roteador->get("/parcerias", "Comum:parcerias");
$roteador->get("/loading", "Comum:loading_pre");
$roteador->get("/pre_login", "Comum:preLogin");

$roteador->get("/cadastro_animal", "Comum:animal");
$roteador->post("/cadastro_animal", "Comum:salvarAnimal");

$roteador->get("/cadastro_usuario", "Comum:usuario");
$roteador->post("/cadastro_usuario", "Comum:salvarUsuario");

$roteador->get("/cadastro_instituicao", "Comum:instituicao");
$roteador->post("/cadastro_instituicao", "Comum:salvarInstituicao");

$roteador->get("/login", "Comum:login");
$roteador->post("/login", "Comum:logar");

$roteador->get("/logout", "Comum:logout");

$roteador->get("/auto_excluir", "Comum:autoExcluir");

$roteador->get("/home_apos_login_usuario", "Comum:home_apos_login_usuario");

$roteador->get("/home_apos_login_instituicao", "Comum:home_apos_login_instituicao");

$roteador->get("/perfil", "Comum:perfil");

$roteador->post("/atualizar_dados", "Comum:atualizarDados");

$roteador->get("/meus_animais", "Comum:meusAnimais");

$roteador->get("/lista", "Comum:lista");

$roteador->get("/quero_adotar", "Comum:queroAdotar");

$roteador->post("/registrar_adocao", "Comum:registrarAdocao");



// Precisa pra rota funcionar
$roteador->dispatch();