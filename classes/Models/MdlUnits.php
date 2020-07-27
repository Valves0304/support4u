<?php
// MdlUnits.php: Model of measure units
// ---------------------------------------------------------------------------
require_once ($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

class MdlUnits
{

    public $unit; // it keeps the last unit handled
    public $unitList = array(); // it keeps the last asked unit list
    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->unit = new Unit();
    }

    // *******************************************************************************************************
    // *** insertUnit
    // *******************************************************************************************************
    // ***
    // *** Insert Unit from a parameter object unit_id
    // ***    - if success, it gets unit_id created from DB and update object
    public static function insertUnit(Unit $unit)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO unit (unit_name)' . ' VALUES ( ? )');

        // add values to the query
        $qryInsert->bind_param('s', $unit->getUnitname());

        // it runs insert query
        if ($qryInsert->execute() === true)
        {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $game->setUnitId(mysqli_insert_id($db));
        }
        else
        {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert Unit Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Insert not perfomed. ');
        }

        return $unit;
    }

    // *******************************************************************************************************
    // *** updateUnit
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateUnit(Unit $unit)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare('UPDATE unit SET unit_name = ? ' . '             WHERE unit_id =?');

        // add values to the query
        $qryUpdate->bind_param('si', $game->getUnitName() , $game->getUnitId());

        // it runs UPDATE query
        if ($qryUpdate->execute() === false)
        {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Error update Unit', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Update not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** listUnits
    // *******************************************************************************************************
    // ***
    // ***
    public static function listUnits($crit = null, $limit = null)
    {
        // get instance from DB
        $db = Db::getInstance();

        // cities array creation
        $unitList = array();

        $query = 'SELECT * FROM unit where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY unit_id';
        if (!is_null($limit))
        {
            $query .= ' LIMIT ' . $limit;
        }

        try
        {
            $result = $db->query($query);
        }
        catch(Exception $e)
        {
            return null;
        }

        if ($result->num_rows == 0)
        {
            return array(
                0 => NULL
            );
        }

        // return array with values
        while ($unit = $result->fetch_assoc())
        {
            $unitData = new Unit();
            $unitData->setUnitId($unit['unit_id']);
            $unitData->setUnitName($unit['unit_name']);

            array_push($unitList, $unitData);
        }

        return $unitList;
    }

    // *******************************************************************************************************
    // *** findUnit
    // *******************************************************************************************************
    // ***
    // *** Return unit based on unit_id
    // ***
    public static function findUnit($unitId)
    {
        return (MdlUnits::listUnits('unit_id = ' . $unitId) [0]);
    }

}
// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Unit
// ***
class Unit
{
    private $unitId;
    private $unitName;

    // getters e setters
    // game_id
    public function getUnitId()
    {
        return $this->unitId;
    }

    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    // game_name
    public function getUnitName()
    {
        return $this->unitName;
    }

    public function setUnitName($unitName)
    {
        $this->unitName = $unitName;
    }

}
