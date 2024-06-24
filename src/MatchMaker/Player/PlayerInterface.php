<?php

namespace App\MatchMaker\Player;

interface PlayerInterface
{
    public function getName(): string;

    public function getRatio(): float;

    public function updateRatioAgainst(self $player, int $result): void;
}
