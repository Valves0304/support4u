<?php
// MdlRequests.php: Model of Request
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');
//require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class MdlRequests
{

    public $request;                 // it keeps the last request handled
    public $requestList = array();   // it keeps the last asked request list

    public $errorCode;
    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->request = new Request();
    }

    // *******************************************************************************************************
    // *** insertRequest
    // *******************************************************************************************************
    // ***
    // *** Insert request from a parameter object request
    // ***    - if success, it gets user_id created from DB and update object
    public static function insertRequest(Request $request)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO request (
            request_numb,
            request_line,
            item,
            quantity,
            req_date,
            user_id_rec,
            user_id_donor,
            req_type,
            req_time,
            lang_id,
            game_type_id,
            req_status)'
        . ' VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');

        // add values to the query
        $qryInsert->bind_param('iisisiiisiii',
                                $request-> getRequestNumb(),
                                $request-> getRequestLine(),
                                $request-> getItem(),
                                $request-> getQuantity(),
                                $request-> getReqDate(),
                                $request-> getUserIdRec(),
                                $request-> getUserIdDonor(),
                                $request-> getReqType(),
                                $request-> getReqTime(),
                                $request-> getLangId(),
                                $request-> getGameId(),
                                $request-> getReqStatus());

        // it runs insert query
        if ($qryInsert->execute() === TRUE) {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $request->setRequestId(mysqli_insert_id($db));
        } else {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert request Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Insert not perfomed. ');
        }

        return $request;
    }

    // *******************************************************************************************************
    // *** updateRequest
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateUser(User $user)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare(' UPDATE user_s4u SET user_pass=? '
                                . '  first_name=? , last_name=? , email=? , age=? , language_id=? , address=?,  city_id=? '
                                . '             WHERE user_login=?');

        // add values to the query
        $qryUpdate->bind_param('sssiisi',
                                $user->getFirst_name(),
                                $user->getLast_name(),
                                $user->getEmail(),
                                $user->getAge(),
                                $user->getLanguage_id(),
                                $user->getAddress(),
                                $user->getCity_id());


        // it runs UPDATE query
        if ($qryUpdate->execute() === FALSE) {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Erro na alteracao da City', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Update not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** listUsers
    // *******************************************************************************************************
    // ***
    // *** It gets a array of users based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listUsers($crit = null, $limit = null) {
        // get instance from DB
        $db = Db::getInstance();

        // users array creation
        $userList = array();

        $query = 'SELECT * FROM user_s4u where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY first_name';
        if (!is_null($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        //echo $query;
        try {
            $result = $db->query($query);
        } catch (Exception $e) {
            return null;
        }

        if ($result->num_rows == 0) {
            return array(0 => NULL);
        }

        // return array with values

        while($user = $result->fetch_assoc()) {

            $userData = new User();
            $userData->setUser_id($user['user_id']);
            $userData->setFirst_name($user['first_name']);
            $userData->setLast_name($user['last_name']);
            $userData->setEmail($user['email']);
            $userData->setAge($user['age']);
            $userData->setLanguage_id($user['language_id']);
            $userData->setAddress($user['address']);
            $userData->setCity_id($user['city_id']);
            $userData->setLatitude($user['latitude']);
            $userData->setLongitude($user['longitude']);

            array_push($userList, $userData);

        }

        return $userList;
    }

    // *******************************************************************************************************
    // *** find user
    // *******************************************************************************************************
    // ***
    // *** Return user based on user_id
    // ***
    public static function findUser($userId) {

        return (MdlUsers::listUsers('user_id = ' . $userId)[0]);
    }
    // *******************************************************************************************************
    // *** find user
    // *******************************************************************************************************
    // ***
    // *** Return user based on user_login
    // ***
    public static function findUserLogin($userLogin) {
        return (MdlUsers::listUsers('user_login = ' . $userLogin)[0]);
    }
    // *******************************************************************************************************
    // *** find user
    // *******************************************************************************************************
    // ***
    // *** Return user based on user email
    // ***
    public static function findUserEmail($userEmail) {
        return (MdlUsers::listUsers('email = ' . $userEmail)[0]);
    }

//
// I dont have forget my password because I dont have way to send email
//

}
// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Request
// ***

class Request
{
    private $request_id;
    private $request_numb;
    private $request_line;
    private $item;
    private $quantity;
    private $req_date;
    private $user_id_rec;
    private $user_id_donor;
    private $req_type;
    private $req_time;
    private $lang_id;
    private $game_id;
    private $req_status;

//****************************************
    public function getRequestId()
    {
      return $this->request_id;
    }
    public function setRequestId($request_id)
    {
      $this->request_id = $request_id;
    }
//****************************************
    public function getRequestNumb()
    {
      return $this->request_numb;
    }
    public function setRequest_numb($request_numb)
    {
      $this->request_numb = $request_numb;
    }
//****************************************
    public function getRequestLine()
    {
      return $this->request_line;
    }
    public function setRequest_line($request_line)
    {
      $this->request_line = $request_line;
    }
//****************************************
    public function getItem()
    {
      return $this->item;
    }
    public function setItem($item)
    {
      $this->item = $item;
    }
//****************************************
    public function getQuantity()
    {
      return $this->quantity;
    }
    public function setQuantity($quantity)
    {
      $this->quantity = $quantity;
    }
//****************************************
    public function getReqDate()
    {
      return $this->req_date;
    }
    public function setReq_date($req_date)
    {
      $this->req_date = $req_date;
    }
//****************************************
    public function getUserIdRec()
    {
      return $this->user_id_rec;
    }
    public function setUser_id_rec($user_id_rec)
    {
      $this->user_id_rec = $user_id_rec;
    }
//****************************************
    public function getUserIdDonor()
    {
      return $this->user_id_donor;
    }
    public function setUser_id_donor($user_id_donor)
    {
      $this->user_id_donor = $user_id_donor;
    }
//****************************************
    public function getReqType()
    {
      return $this->req_type;
    }
    public function setReq_type($req_type)
    {
      $this->req_type = $req_type;
    }
//****************************************
    public function getReqTime()
    {
      return $this->req_time;
    }
    public function setReq_time($req_time)
    {
      $this->req_time = $req_time;
    }
//****************************************
    public function getLangId()
    {
      return $this->lang_id;
    }
      public function setLang_id($lang_id)
    {
      $this->lang_id = $lang_id;
    }
//****************************************
    public function getGameId()
    {
      return $this->game_id;
    }
    public function setGame_id($game_id)
    {
      $this->game_id = $game_id;
    }
//****************************************
    public function getReqStatus()
    {
      return $this->req_status;
    }
    public function setReq_status($req_status)
    {
      $this->req_status = $req_status;
    }
  }
