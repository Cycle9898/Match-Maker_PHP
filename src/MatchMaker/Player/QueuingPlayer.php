<?php

namespace App\MatchMaker\Player;

class QueuingPlayer extends Player
{
    public function __construct(PlayerInterface $pLayer, protected int $range = 1)
    {
        parent::__construct($pLayer->getName(), $pLayer->getRatio());
    }

    public function getRange(): int
    {
        return $this->range;
    }

    public function upgradeRange(): void
    {
        $this->range = min($this->range + 1, 40);
    }
}
