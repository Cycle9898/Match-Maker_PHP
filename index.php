<?php
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

class Player
{
    protected string $name;
    protected float $ratio = 400.0;

    public function __construct(string $name, float $ratio)
    {
        $this->name = $name;
        $this->ratio = $ratio;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRatio(): float
    {
        return round($this->ratio, 2);
    }

    private function probabilityAgainst(self $player): float
    {
        return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
    }

    public function updateRatioAgainst(self $player, int $result): void
    {
        $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
    }
}

class QueuingPlayer extends Player
{
    public function __construct(PLayer $pLayer, protected int $range = 1)
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

$player1 = new Player('José', 400);
$player2 = new Player('Jade', 450);

$lobby = new Lobby();
$lobby->addPlayers($player1, $player2);

// test findOpponents method, should find player2
echo var_dump($lobby->findOpponents($lobby->queuingPlayers[0])) . "<br />";

echo "Niveau initial {$player1->getName()}: {$player1->getRatio()}<br/>";
echo "Niveau initial {$player2->getName()}: {$player2->getRatio()}<br/>";

// If player1 wins
echo "{$player1->getName()} a gagné !<br/>";
$player1->updateRatioAgainst($player2, 1);
$player2->updateRatioAgainst($player1, -1);

echo "Niveau actualisé {$player1->getName()}: {$player1->getRatio()}<br/>";
echo "Niveau actualisé {$player2->getName()}: {$player2->getRatio()}<br/>";

exit(0);
