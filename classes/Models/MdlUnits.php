<?php
// MdlUnits.php: Model of measure units
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');
//require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class MdlUnits
{

    public $unit;                 // it keeps the last unit handled
    public $unitList = array();  // it keeps the last asked unit list

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

        // add values to the query

        // it runs insert query


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

        // add values to the query

        // it runs UPDATE query

    }

    // *******************************************************************************************************
    // *** listUnits
    // *******************************************************************************************************
    // ***
    // *** It gets a array of units based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listUnits($crit = null, $limit = null) {
        // get instance from DB
        $db = Db::getInstance();

        // unit array creation
        $unitList = array();

        $query = 'SELECT * FROM unit where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY unit_description';
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
        while($unit = $result->fetch_assoc()) {
            $unitData = new Unit();
            $unitData->setUnitId($unit['unit_id']);
            $unitData->setUnitDesc($unit['unit_description']);

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
    public static function findUnit($unitId) {
        return (MdlUnits::listUnits('unit_id = ' . $unitId)[0]);
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
    private $unit_id;
    private $unit_description;

    // getters e setters

    // unit_id
    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    // unit_description
    public function getUnitDesc()
    {
        return $this->unit_description;
    }

    public function setUnitDesc($unit_description)
    {
        $this->unit_description = $unit_description;
    }

}
