<?php

namespace App\MatchMaker\Player;

abstract class BasePlayer implements PlayerInterface
{
    protected string $name;
    protected float $ratio;

    public function __construct(string $name, float $ratio = 400.0)
    {
        $this->name = $name;
        $this->ratio = $ratio;
    }

    abstract public function getName(): string;

    abstract public function getRatio(): float;

    abstract protected function probabilityAgainst(PlayerInterface $player): float;

    abstract public function updateRatioAgainst(PlayerInterface $player, int $result): void;
}
