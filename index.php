<?php

const RESULT_WINNER = 1;
const RESULT_LOSER = -1;
const RESULT_DRAW = 0;
const RESULT_POSSIBILITIES = [RESULT_WINNER, RESULT_LOSER, RESULT_DRAW];

class Encounter
{
    public function getProbabilityAgainst(int $levelPlayerOne, int $againstLevelPlayerTwo): float
    {
        return 1 / (1 + (10 ** (($againstLevelPlayerTwo - $levelPlayerOne) / 400)));
    }

    public function setNewLevel(int &$levelPlayerOne, int $againstLevelPlayerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s', implode(' or ', RESULT_POSSIBILITIES)));
        }

        $levelPlayerOne += (int) (32 * ($playerOneResult - $this->getProbabilityAgainst($levelPlayerOne, $againstLevelPlayerTwo)));
    }
}

$greg = 400;
$jade = 800;

echo "Niveau initial Greg: $greg<br/>";
echo "Niveau initial Jade: $jade<br/>";

$encounter = new Encounter;

echo sprintf(
    'Greg à %.2f%% chance de gagner face à Jade.<br/>',
    $encounter->getProbabilityAgainst($greg, $jade) * 100
) . PHP_EOL;

// If Greg wins
echo 'Greg a gagné !<br/>';
$encounter->setNewLevel($greg, $jade, RESULT_WINNER);
$encounter->setNewLevel($jade, $greg, RESULT_LOSER);

echo "Niveau actualisé Greg: $greg<br/>";
echo "Niveau actualisé Jade: $jade<br/>";

exit(0);
