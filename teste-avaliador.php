<?php

require_once "vendor/autoload.php";

use Alura\Leilao\Service\Avaliador;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Model\Lance;

//Arrumo a casa para a execução Arrenge - Given

$leilao = new Leilao('Fiat 147 0KM');

$umUsuario = new Usuario('Maria');
$doisUsuario = new Usuario('Joao');

$lance1 = new Lance($umUsuario, 500);
$lance2 = new Lance($doisUsuario, 20);
$avalia = new Avaliador();

//Executa as funções do código Act - When
$avalia->avalia($leilao);

$leilao->recebeLance($lance1);
$leilao->recebeLance($lance2);

$ganhou = $avalia->getMaiorValor();

echo $ganhou . PHP_EOL;

//teria um código verificador se deu tudo certo Assert - Then