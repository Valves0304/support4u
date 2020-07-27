<?php
// MdlCity.php: Model of City
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');


class MdlCities
{

    public $city;                 // it keeps the last city handled
    public $cityList = array();  // it keeps the last asked cities list

    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->city = new City();
    }

    // *******************************************************************************************************
    // *** insertCity
    // *******************************************************************************************************
    // ***
    // *** Insert City from a parameter object city
    // ***    - if success, it gets city_id created from DB and update object
    public static function insertCity(City $city)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO city (city_name)'
                                . ' VALUES ( ? )');

        // add values to the query
        $qryInsert->bind_param('s',
                                $city->getCityName());

        // it runs insert query
        if ($qryInsert->execute() === TRUE) {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $city->setCityId(mysqli_insert_id($db));
        } else {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert city Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Insert not perfomed. ');
        }

        return $city;
    }

    // *******************************************************************************************************
    // *** updateCity
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateCity(City $city)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare('UPDATE city SET city_name = ? ' .
                                  '             WHERE city_id =?');

        // add values to the query
        $qryUpdate->bind_param('si',
                                $city->getCityName(),
                                $city->getCityId());


        // it runs UPDATE query
        if ($qryUpdate->execute() === FALSE) {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Erro na alteracao da City', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Update not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** listCities
    // *******************************************************************************************************
    // ***
    // *** It gets a array of cities based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listCities($crit = null, $limit = null) {
        // get instance from DB
        $db = Db::getInstance();

        // cities array creation
        $cityList = array();

        $query = 'SELECT * FROM city where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY city_name';
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
        while($city = $result->fetch_assoc()) {
            $cityData = new City();
            $cityData->setCityId($city['city_id']);
            $cityData->setCityName($city['city_name']);

            array_push($cityList, $cityData);
        }

        return $cityList;
    }

    // *******************************************************************************************************
    // *** findCity
    // *******************************************************************************************************
    // ***
    // *** Return city based on city_id
    // ***
    public static function findCity($cityId) {
        return (MdlCities::listCities('city_id = ' . $cityId)[0]);
    }



}

// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines City
// ***

class City
{
    private $city_id;
    private $city_name;

    // getters e setters

    // city_id
    public function getCityId()
    {
        return $this->city_id;
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    // city_name
    public function getCityName()
    {
        return $this->city_name;
    }

    public function setCityName($city_name)
    {
        $this->city_name = $city_name;
    }

}
