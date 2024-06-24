<?php

spl_autoload_register(function (string $fQCN): void {
    $path = str_replace(["App", "\\"], ["src", "/"], $fQCN) . ".php";

    require_once($path);
});

use App\MatchMaker\Player\Player;
use App\MatchMaker\Lobby;

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
