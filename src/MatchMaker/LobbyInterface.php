<?php

namespace App\MatchMaker;

use App\MatchMaker\Player\PlayerInterface;

interface LobbyInterface
{
    public function isPlaying(PlayerInterface $player): bool;

    public function addPlayer(PlayerInterface $player): void;
}
