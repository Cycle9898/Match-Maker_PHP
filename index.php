<?php
class Encounter
{
    public const RESULT_WINNER = 1;
    public const RESULT_LOSER = -1;
    public const RESULT_DRAW = 0;
    private const RESULT_POSSIBILITIES = [self::RESULT_WINNER, self::RESULT_LOSER, self::RESULT_DRAW];

    public static function getProbabilityAgainst(Player $playerOne, Player $playerTwo): float
    {
        return 1 / (1 + (10 ** (($playerTwo->getLevel() - $playerOne->getLevel()) / 400)));
    }

    public static function setNewLevel(Player &$playerOne, Player $playerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, self::RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s', implode(' or ', self::RESULT_POSSIBILITIES)));
        }

        $playerOne->setLevel(
            $playerOne->getLevel() +
                round(32 * ($playerOneResult - self::getProbabilityAgainst($playerOne, $playerTwo)))
        );
    }
}

class Player
{
    private int $level;

    public function __construct(int $level)
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}

$greg = new Player(400);
$jade = new Player(800);

echo "Niveau initial Greg: {$greg->getLevel()}<br/>";
echo "Niveau initial Jade: {$jade->getLevel()}<br/>";

echo sprintf(
    'Greg à %.2f %% chance de gagner face à Jade.<br/>',
    Encounter::getProbabilityAgainst($greg, $jade) * 100
) . PHP_EOL;

// If Greg wins
echo 'Greg a gagné !<br/>';
Encounter::setNewLevel($greg, $jade, Encounter::RESULT_WINNER);
Encounter::setNewLevel($jade, $greg, Encounter::RESULT_LOSER);

echo "Niveau actualisé Greg: {$greg->getLevel()}<br/>";
echo "Niveau actualisé Jade: {$jade->getLevel()}<br/>";

exit(0);
