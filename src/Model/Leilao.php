<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance) : void
    {
       /* if (!empty($this->lances) && $this->ehDoUltimoLace($lance)) {
            throw new \DomainException('Usuário não pode fazer 2 lances seguidos');
        }

        $usuario = $lance->getUsuario();

        $totalLanceUsuario = $this->so5Lances($usuario);

        if ($totalLanceUsuario >= 5) {
            throw new \DomainException('Usuário não pode fazer mais de 5 lances');
        }
        $this->lances[] = $lance;*/

        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $totalLancesUsuario = $this
            ->quantidadeLancesPorUsuario($lance->getUsuario());
        if ($totalLancesUsuario >= 5) {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilão');
        }

        $this->lances[] = $lance;
    }


    private function ehDoUltimoUsuario(Lance $lance) {
        $ultimoLance = $this->lances[count($this->lances) - 1];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        $totalLanceUsuario = array_reduce($this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() === $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0);
        return $totalLanceUsuario;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado()
    {
        return $this->finalizado;
    }
}
