<?php

namespace Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private $avaliador;
    protected function setUp() : void
    {
       $this->avaliador = new Avaliador();
    }
    
    /**
     * @dataProvider leilao
     */
    public function testeAvaliadorEncontraMaiorValor(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);

        $maiorValor = $this->avaliador->getMaiorValor();

        self::assertEquals(500,$maiorValor);   //Método da classe TestCase
    }

    /**
     * @dataProvider leilao
     */
    public function testeAvaliadorEncontraMenorValor(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);

        $menorValor = $this->avaliador->getMenorValor();

        self::assertEquals(400,$menorValor);   //Método da classe TestCase
    }

    /**
     * @dataProvider leilao
     */
    public function testeTresMaioresLances(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);

        $maioresLances = $this->avaliador->getMaioresLances();
        static::assertCount(3, $maioresLances);
        static::assertEquals(500, $maioresLances[0]->getValor());
        static::assertEquals(450, $maioresLances[1]->getValor());
        static::assertEquals(400, $maioresLances[2]->getValor());
    }

    public function testeLeilaoVazio()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão vazio!');
        $leilao = new Leilao('Fusca 1978 OKM');
        $this->avaliador->avalia($leilao);
    }

    public function testeLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão não pode ser avaliado após finalizado');

        $leilao = new Leilao('Fusca 1978 OKM');
        $leilao->recebeLance(new Lance(new Usuario('Jonas'), 3400));
        $leilao->finaliza();
        $this->avaliador->avalia($leilao);
    }

    /*-------- DADOS ---------*/
    public static function leilao()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $umUsuario = new Usuario('Maria');
        $doisUsuario = new Usuario('Joao');
        $tresUsuario = new Usuario('Ana');

        $lance1 = new Lance($umUsuario, 500);
        $lance2 = new Lance($doisUsuario, 450);
        $lance3 = new Lance($tresUsuario, 400);

        $leilao->recebeLance($lance1);
        $leilao->recebeLance($lance2);
        $leilao->recebeLance($lance3);

        return [
            'ordem de leilao' => [$leilao]
        ];
    }

    /*public static function entregaLeiloes()
    {
        $avaliadorTest = new AvaliadorTest("AvaliadorTest");
        return [
            [$avaliadorTest->leilao()]
        ];
    }*/
}