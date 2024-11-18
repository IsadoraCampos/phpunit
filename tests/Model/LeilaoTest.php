<?php

namespace Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /**
     * @dataProvider geraLance
     */

    public function testLeilaoRecebeLance(int $quantLance, Leilao $leilao, array $valores)
    {
        static::assertCount($quantLance,$leilao->getLances());

        foreach ($valores as $i =>  $valor) {
            static::assertEquals($valor, $leilao->getLances()[$i]->getValor());
        }
    }

    public function testLeilaoNaoRecebeLanceRepetido()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');

        $leilao = new Leilao('Fusca 1978 0KM');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 4500));
        $leilao->recebeLance(new Lance($ana, 4900));
    }

    public function testNaoPode5Lances()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Fusca 1978 0KM');
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 4500));
        $leilao->recebeLance(new Lance($maria, 4900));
        $leilao->recebeLance(new Lance($joao, 4990));
        $leilao->recebeLance(new Lance($maria, 5000));
        $leilao->recebeLance(new Lance($joao, 5100));
        $leilao->recebeLance(new Lance($maria, 5200));
        $leilao->recebeLance(new Lance($joao, 5300));
        $leilao->recebeLance(new Lance($joao, 5500));
        $leilao->recebeLance(new Lance($maria, 5600));

        $leilao->recebeLance(new Lance($joao, 5700));

        /*static::assertCount(9,$leilao->getLances());
        static::assertEquals(5600,$leilao->getLances()[7]->getValor());*/
    }

    public static function geraLance()
    {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 149 OKM');

        $lance1 = new Lance($joao, 2300);
        $lance2 = new Lance($maria, 2500);

        $leilaoCom2Lances->recebeLance($lance1);
        $leilaoCom2Lances->recebeLance($lance2);

        $leilaoCom1Lance = new Leilao('Fusca 1978 OKM');
        $leilaoCom1Lance->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2,$leilaoCom2Lances, [2300], [2500]],
            '1-lance' => [1, $leilaoCom1Lance, [5000]]
        ];
    }
}