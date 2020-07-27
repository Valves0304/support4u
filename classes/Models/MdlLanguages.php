<?php
// MdlLanguages.php: Model of language
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

class MdlLanguages
{

    public $lang;                 // it keeps the last game handled
    public $langList = array();  // it keeps the last asked games list

    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->language = new Language();
    }

    // *******************************************************************************************************
    // *** insertLang
    // *******************************************************************************************************
    // ***
    // *** Insert Game from a parameter object game_id
    // ***    - if success, it gets game_id created from DB and update object
    public static function insertLang(Language $lang)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO language (langName)'
                                . ' VALUES ( ? )');

        // add values to the query
        $qryInsert->bind_param('s',
                                $game->getLangNname());

        // it runs insert query
        if ($qryInsert->execute() === TRUE) {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $game->setLangId(mysqli_insert_id($db));
        } else {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert Language Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Insert not perfomed. ');
        }

        return $lang;
    }

    // *******************************************************************************************************
    // *** updateGame
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateLang(Language $lang)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare('UPDATE language SET lang_name = ? ' .
                                  '             WHERE lang_id =?');

        // add values to the query
        $qryUpdate->bind_param('si',
                                $game->getLangName(),
                                $game->getLangId());


        // it runs UPDATE query
        if ($qryUpdate->execute() === FALSE) {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Error upadate Language', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Update not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** ListLangs
    // *******************************************************************************************************
    // ***
    // *** It gets a array of cities based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listLangs($crit = null, $limit = null) {
        // get instance from DB
        $db = Db::getInstance();

        // cities array creation
        $langList = array();

        $query = 'SELECT * FROM language where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY lang_name';
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
        while($lang = $result->fetch_assoc()) {
            $langData = new Language();
            $langData->setLangId($lang['lang_id']);
            $langData->setLangName($lang['lang_name']);

            array_push($langList, $langData);
        }

        return $langList;
    }

    // *******************************************************************************************************
    // *** findLanguage
    // *******************************************************************************************************
    // ***
    // *** Return language based on language_id
    // ***
    public static function findLanguage($langId) {
        return (MdlLanguages::listLangs('lang_id = ' . $langId)[0]);
    }

}
// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Language
// ***

class Language
{
    private $langId;
    private $langName;

    // getters e setters

    // game_id
    public function getLangId()
    {
        return $this->langId;
    }

    public function setLangId($langId)
    {
        $this->langId = $langId;
    }

    // game_name
    public function getLangName()
    {
        return $this->langName;
    }

    public function setLangName($langName)
    {
        $this->langName = $langName;
    }

}
