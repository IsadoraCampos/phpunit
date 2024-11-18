<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private $maiorValor = -INF;
    private $menorValor = INF;
    private array $maioresLances;

    public function avalia(Leilao $leilao) : void
    {
        $lances = $leilao->getLances();

        if ($leilao->estaFinalizado()) {
            throw new \DomainException('Leilão não pode ser avaliado após finalizado');
        }

        if (empty($lances)) {
            throw new \DomainException('Não é possível avaliar um leilão vazio!');
        }
        foreach ($lances as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }
            if ($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
        }

        usort($lances, function (Lance $lance1, Lance $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });

        $this->maioresLances = array_slice($lances,0,3); //0 onde começa, e quantos itens quero pegar
    }

    /**
     * @return mixed
     */
    public function getMaiorValor() : float
    {
        return $this->maiorValor;
    }

    /**
     * @return mixed
     */
    public function getMenorValor() : float
    {
        return $this->menorValor;
    }

    /**
     * @return array
     */
    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}