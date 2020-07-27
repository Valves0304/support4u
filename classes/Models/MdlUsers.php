<?php
// MdlUsers.php: Model of User
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');
//require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class MdlUsers
{

    public $user;                 // it keeps the last user handled
    public $userList = array();   // it keeps the last asked user list

    public $errorCode;
    public $errorField;
    public $errorMsg;

    public function __construct()
    {
        $this->user = new User();
    }

    // *******************************************************************************************************
    // *** insertUser
    // *******************************************************************************************************
    // ***
    // *** Insert user from a parameter object user
    // ***    - if success, it gets user_id created from DB and update object
    public static function insertUser(User $user)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO user_s4u (
            first_name,
            last_name,
            email,
            age,
            language_id,
            address,
            city_id,
            latitude,
            longitude,
            user_login,
            user_pass)'
        . ' VALUES (?,?,?,?,?,?,?,?,?,?,?)');

        // add values to the query
        $qryInsert->bind_param('sssiisissss',
                                $user->getFirstName(),
                                $user->getLasttName(),
                                $user->getEmail(),
                                $user->getAge(),
                                $user->getLanguageId(),
                                $user->getAddress(),
                                $user->getCityId(),
                                $user->getLatitude(),
                                $user->getLongitude(),
                                $user->getUserLogin(),
                                $user->getCryptUser_pass());

        // it runs insert query
        if ($qryInsert->execute() === TRUE) {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $city->setUser_id(mysqli_insert_id($db));
        } else {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert user Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception ('Data Base Fails. Insert not perfomed. ');
        }

        return $user;
    }

    // *******************************************************************************************************
    // *** updateUser
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateUser(User $user)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare(' UPDATE user_s4u SET user_pass=? ,'
                                . '  first_name=? , last_name=? , email=? , age=? , language_id=? , address=?,  city_id=?, latitude=?, longitude=? '
                                . '             WHERE user_id=?');

                                    $pass=$user->getCryptUserPass();
                                    $fname =$user->getFirstName();
                                    $lname=$user->getLasttName();
                                    $email=$user->getEmail();
                                    $age=$user->getAge();
                                    $lang=$user->getLanguageId();
                                    $address=$user->getAddress();
                                    $city=$user->getCityId();
                                    $lat=$user->getLatitude();
                                    $long=$user->getLongitude();
                                    $id=$user->getUserId();

        // add values to the query
        $qryUpdate->bind_param('ssssiisissi',
                                $pass,
                                $fname,
                                $lname,
                                $email,
                                $age,
                                $lang,
                                $address,
                                $city,
                                $lat,
                                $long,
                                $id);

        // it runs UPDATE query
        if ($qryUpdate->execute() === FALSE) {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Error User Update', 3, getenv('LOG_FILE'));
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
            $userData->setCryptUserPass($user['user_pass']);
            $userData->setFirstName($user['first_name']);
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
        return (MdlUsers::listUsers('user_login = "'.  $userLogin.'"')[0]);
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

public function registerUser()
{

    // GET values
    $userLogin  = isset($_GET['usuario']) ? $_GET['usuario'] : FALSE;

    // condições de autorização INTERESSANTE!!!!
    //if (($userLogin AND $userLogin != $_SESSION['USUARIO'])  {                // apenas adms podem alterar por idAtleta
    //    $this->view = new ViewPagesSemPermissao();
    //    return;
    //}

    // prepara o modelo
    $this->model->user = new User();

    // carrega informações atuais na tela
    if ($userLogin) {
        $this->model->user = MdlUsers::findUserLogin($userLogin);
    }

    $this->view = new ViewUserRegister($this->model);


    // exibe informações para atualização/inclusão
    //$this->view = new ViewAtletasAtualizaCadastro($this->model);
}


}
// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines User
// ***

class User
{
    private	$user_id;
    private	$first_name;
    private	$last_name;
    private	$email;
    private	$age;
    private	$language_id;
    private	$address;
    private	$city_id;
    private	$latitude;
    private	$longitude;
    private $user_login;
    private $user_pass;

    // getters e setters
    //	User_id
    public function getUserId()
    {
      return $this->user_id;
    }
    public function setUser_id($user_id)
    {
      $this->user_id  = $user_id;
    }

    //	First_name
    public function getFirstName()
    {
      return $this->first_name;
    }
    public function setFirstName($first_name)
    {
      $this->first_name = $first_name;
    }

    //	Last_name
    public function getLasttName()
    {
      return $this->last_name;
    }
    public function setLast_name($last_name)
    {
      $this->last_name = $last_name;
    }

    //	Email
    public function getEmail()
    {
      return $this->email;
    }
    public function setEmail($email)
    {
      $this->email = $email;
    }

    //	Age
    public function getAge()
    {
      return $this->age;
    }
    public function setAge($age)
    {
      $this->age = $age;
    }

    //	Language_id
    public function getLanguageId()
    {
      return $this->language_id;
    }
    public function setLanguage_id($language_id)
    {
      $this->language_id = $language_id;
    }

    //	Address
    public function getAddress()
    {
      return $this->address;
    }
    public function setAddress($address)
    {
      $this->address = $address;
    }
    //	City_id
    public function getCityId()
    {
      return $this->city_id;
    }
    public function setCity_id($city_id)
    {
      $this->city_id = $city_id;
    }
    //	Latitude
    public function getLatitude()
    {
      return $this->latitude;
    }
    public function setLatitude($latitude)
    {
      $this->latitude = $latitude;
    }

    //	Longitude
    public function getLongitude()
    {
      return $this->longitude;
    }
    public function setLongitude($longitude)
    {
      $this->longitude = $longitude;
    }

    //	User_login
    public function getUserLogin()
    {
      return $this->user_login;
    }
    public function setUserLogin($user_login)
    {
      $this->user_login = $user_login;
    }

    //	User_pass
    public function checkUserPass($pass)
    {
      if (Util::hash_equals($this->user_pass, crypt($pass, $this->user_pass)))  {
        return true;
      } else {
        return false;
      }
    }
    public function getCryptUserPass()
    {
      return $this->user_pass;
    }

    public function setCryptUserPass($cryptPass)
    {
      $this->user_pass = $cryptPass;
    }

    public function setUserPass($pass)
        {
            // process' cost
            $cost = 10;

            // random  "sal"
            $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

            // "$2a$" =  Blowfish Algorithm
            $salt = sprintf("$2a$%02d$", $cost) . $salt;

            // crypto pass
            $this->user_pass = crypt($pass, $salt);


            //$this->setObsUsuario($pass);

            // atualiza a data de expiração da senha
            //$this->setDataExpiracaoSenha((new DateTimeImmutable())->add(new DateInterval(Usuario::PRAZO_EXPIRACAO_SENHA)));
        }
}
