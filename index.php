<?php

namespace App\MathMaker;

use App\MathMaker\Player\Player;
use App\MathMaker\Player\QueuingPlayer;

class Lobby
{
    /**
     * @var array<QueuingPLayer>
     */
    public array $queuingPlayers = [];

    public function findOpponents(QueuingPlayer $player): array
    {
        $minLevel = round($player->getRatio() / 100);
        $maxLevel = $minLevel + $player->getRange();

        return array_filter($this->queuingPlayers, static function (QueuingPlayer $potentialOpponent) use ($minLevel, $maxLevel, $player) {
            $playerLevel = round($potentialOpponent->getRatio() / 100);

            return $player !== $potentialOpponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
        });
    }

    public function addPlayer(Player $player): void
    {
        $this->queuingPlayers[] = new QueuingPlayer($player);
    }

    public function addPlayers(Player ...$players): void
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
    }
}

namespace App\MathMaker\Player;

abstract class BasePlayer
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

    abstract protected function probabilityAgainst(self $player): float;

    abstract public function updateRatioAgainst(self $player, int $result): void;
}

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

class QueuingPlayer extends Player
{
    public function __construct(BasePlayer $pLayer, protected int $range = 1)
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

class BlitzPlayer extends Player
{
    public function __construct(public string $name, public float $ratio = 1200.0)
    {
        parent::__construct($name, $ratio);
    }

    public function updateRatioAgainst(BasePlayer $player, int $result): void
    {
        // ranking is 4 times faster (128 instead of 32)
        $this->ratio += 128 * ($result - $this->probabilityAgainst($player));
    }
}

namespace App;

use App\MathMaker\Player\Player;
use App\MathMaker\Lobby;

$player1 = new Player('José', 400);
$player2 = new Player('Jade', 450);

$lobby = new Lobby();
$lobby->addPlayers($player1, $player2);

// Test findOpponents method, should find player2
echo var_dump($lobby->findOpponents($lobby->queuingPlayers[0])) . "<br />";

// Test display
echo "Niveau initial {$player1->getName()}: {$player1->getRatio()}<br/>";
echo "Niveau initial {$player2->getName()}: {$player2->getRatio()}<br/>";

// if player1 wins
echo "{$player1->getName()} a gagné !<br/>";
$player1->updateRatioAgainst($player2, 1);
$player2->updateRatioAgainst($player1, -1);

echo "Niveau actualisé {$player1->getName()}: {$player1->getRatio()}<br/>";
echo "Niveau actualisé {$player2->getName()}: {$player2->getRatio()}<br/>";

exit(0);
