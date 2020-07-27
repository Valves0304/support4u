<?php
// MdlGames.php: Model of game
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');
//require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class MdlGames
{

    public $game;                 // it keeps the last game handled
    public $gameList = array();  // it keeps the last asked games list

    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->game = new Game();
    }

    // *******************************************************************************************************
    // *** insertGame
    // *******************************************************************************************************
    // ***
    // *** Insert Game from a parameter object game_id
    // ***    - if success, it gets game_id created from DB and update object
    public static function insertGame(Game $game)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO game (game_name)'
                                . ' VALUES ( ? )');

        // add values to the query
        $qryInsert->bind_param('s',
                                $game->getGame_name());

        // it runs insert query
        if ($qryInsert->execute() === TRUE) {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $game->setGame_id(mysqli_insert_id($db));
        } else {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert game Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Insert not perfomed. ');
        }

        return $game;
    }

    // *******************************************************************************************************
    // *** updateGame
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateGame(Game $game)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare('UPDATE game SET game_name = ? ' .
                                  '             WHERE game_id =?');

        // add values to the query
        $qryUpdate->bind_param('si',
                                $game->getGame_name(),
                                $game->getGame_id());


        // it runs UPDATE query
        if ($qryUpdate->execute() === FALSE) {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Error upadate Game', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Update not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** listGames
    // *******************************************************************************************************
    // ***
    // *** It gets a array of cities based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listGames($crit = null, $limit = null) {
        // get instance from DB
        $db = Db::getInstance();

        // cities array creation
        $gameList = array();

        $query = 'SELECT * FROM game where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY game_name';
        if (!is_null($limit)) {
            $query .= ' LIMIT ' . $limit;
        }

        try {
            $result = $db->query($query);
        } catch (Exception $e) {
            return null;
        }

        if ($result->num_rows == 0) {
            return array(0 => NULL);
        }

        // return array with values
        while($game = $result->fetch_assoc()) {
            $gameData = new Game();
            $gameData->setGame_id($game['game_id']);
            $gameData->setGame_name($game['game_name']);

            array_push($gameList, $gameData);
        }

        return $gameList;
    }

    // *******************************************************************************************************
    // *** findGame
    // *******************************************************************************************************
    // ***
    // *** Return game based on game_id
    // ***
    public static function findGame($gameId) {
        return (MdlGames::listGames('game_id = ' . $gameId)[0]);
    }

}
// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Game
// ***

class Game
{
    private $game_id;
    private $game_name;

    // getters e setters

    // game_id
    public function getGame_id()
    {
        return $this->game_id;
    }

    public function setGame_id($game_id)
    {
        $this->game_id = $game_id;
    }

    // game_name
    public function getGame_name()
    {
        return $this->game_name;
    }

    public function setGame_name($game_name)
    {
        $this->game_name = $game_name;
    }

}
