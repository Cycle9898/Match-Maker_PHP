<?php

namespace App\MatchMaker\Player;

class Player extends BasePlayer
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getRatio(): float
    {
        return round($this->ratio, 2);
    }

    protected function probabilityAgainst(BasePlayer $player): float
    {
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }

    public function updateRatioAgainst(BasePlayer $player, int $result): void
    {
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }
}
