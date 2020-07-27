<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlGames.php';

// encontrar game
$game = MdlGames::findGame(1);

echo '<BR> Game: ' . $game->getGame_name();

//inclui game
$gameNovo = new Game();
$gameNovo->setGame_name("Atari");
MdlGames::insertGame($gameNovo);

//listar all games
$list = MdlGames::listGames();

foreach($list as $game) {
    echo "<BR>" . $game->getGame_id() . " - " . $game->getGame_name();
}
