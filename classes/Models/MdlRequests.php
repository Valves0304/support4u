<?php
// MdlRequests.php: Model of Request
// ---------------------------------------------------------------------------
require_once ($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

class MdlRequests
{

    public $request; // it keeps the last request handled
    public $requestList = array(); // it keeps the last asked request list
    public $errorCode;
    public $errorField;
    public $errorMsg;

    //Status of request
    const ACTIVE_REQUEST = 1;
    const IN_PROGRESS_REQUEST = 2;
    const ENDED_REQUEST = 3;

    //Type of request
    const GROCERY_REQUEST = 1;
    const TIME_REQUEST = 2;

    //Best time to receive a request
    const MORNING = 1;
    const AFTERNOON = 2;
    const NIGHT = 3;
    const ANYTIME = 4;

    //Type of TIME_REQUEST
    const TALK = 1;
    const GAME = 2;
    const DOG = 3;
    const OTHER = 4;

    public function __construct()
    {
        $this->request = new Request();
    }

    // *******************************************************************************************************
    // *** insertRequestItem
    // *******************************************************************************************************
    // ***
    // *** Insert requestItem from a parameter object requestItem
    // ***    - if success, it gets item_id created from DB and update object
    public static function insertRequestItem(RequestItem $requestItem)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates INSERT Query
        $qryInsert = $db->prepare('INSERT INTO request_items (
                                    request_id,
                                    type_time,
                                    best_time,
                                    lang_id,
                                    item,
                                    phone,
                                    game_id,
                                    game_name,
                                    unit_id,
                                    quantity)' . ' VALUES (?,?,?,?,?,?,?,?,?,?)');
        // add values to the query
        error_reporting(E_ALL & ~E_NOTICE);
        $qryInsert->bind_param('iiiissisii', $requestItem->getRequestId() , $requestItem->getTypeTime() , $requestItem->getBestTime() , $requestItem->getLangId() , $requestItem->getItem() , $requestItem->getPhone() , $requestItem->getGameId() , $requestItem->getGameName() , $requestItem->getUnitId() , $requestItem->getQuantity());
        error_reporting(E_ALL);

        // it runs insert query
        if ($qryInsert->execute() === true)
        {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $requestItem->setItemsId(mysqli_insert_id($db));
        }
        else
        {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert request Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . var_dump($qryInsert->error_list) , 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Insert not perfomed. ');
        }

        return $requestItem;
    }

    // *******************************************************************************************************
    // *** deleteRequestItem
    // *******************************************************************************************************
    public static function deleteRequestItem($items_id)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryDelete = $db->prepare(' DELETE FROM request_items WHERE items_id=?');

        // add values to the query
        $qryDelete->bind_param('i', $items_id);
        // it runs UPDATE query
        if ($qryDelete->execute() === false)
        {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Fail Request Item Delete', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Delete not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** deleteRequestItemByRequestId
    // *******************************************************************************************************
    // deletes all request items for a given request_id
    public static function deleteRequestItemByRequestId($request_id)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryDelete = $db->prepare(' DELETE FROM request_items WHERE request_id=?');

        // add values to the query
        $qryDelete->bind_param('i', $request_id);
        // it runs UPDATE query
        if ($qryDelete->execute() === false)
        {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Fail Request Item Delete', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Delete not perfomed. ');
        }
    }

    // *******************************************************************************************************
    // *** listRequestItems
    // *******************************************************************************************************
    // ***
    // *** It gets an array of requestItems based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listRequestItems($crit = null, $limit = null)
    {
        // get instance from DB
        $db = Db::getInstance();

        // users array creation
        $requestItemList = array();

        $query = 'SELECT * FROM request_items where 1=1 ' . (is_null($crit) ? '' : 'and ' . $crit);
        $query .= ' ORDER BY items_id';
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
        while ($requestItem = $result->fetch_assoc())
        {
            $requestItemData = new RequestItem();

            $langName = "";

             if($requestItem['lang_id'] != NULL){
              $languageData = new Language();
              $languageData = MdlLanguages::findLanguage($requestItem['lang_id']);
              $langName = $languageData->getLangName();
            }


            $requestItemData->setItemsId($requestItem['items_id']);
            $requestItemData->setRequestId($requestItem['request_id']);
            $requestItemData->setBestTime(MdlRequests::translateBestTime($requestItem['best_time']));
            $requestItemData->setTypeTime($requestItem['type_time']);
            $requestItemData->setLangId($requestItem['lang_id']);
            $requestItemData->setLangName($langName);
            $requestItemData->setItem($requestItem['item']);
            $requestItemData->setPhone($requestItem['phone']);
            $requestItemData->setGameId($requestItem['game_id']);
            $requestItemData->setGameName($requestItem['game_name']);
            $requestItemData->setUnitId($requestItem['unit_id']);
            $requestItemData->setQuantity($requestItem['quantity']);

            //The array_push() function inserts one or more elements to the end of an array.
            array_push($requestItemList, $requestItemData);

        }

        return $requestItemList;
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
                                    request_id,
                                    req_type,
                                    req_date,
                                    user_id_req,
                                    user_id_donor,
                                    price,
                                    status)' . ' VALUES (?,?,?,?,?,?,?)');
        // add values to the query
        error_reporting(E_ALL & ~E_NOTICE);
        $qryInsert->bind_param('iisiisi', $request->getRequestId() , $request->getRequestType() , $request->getRequestDate() , $request->getUserIdReq() , $request->getUserIdDonor() , $request->getPrice() , $request->getStatusRequest());
        error_reporting(E_ALL);


        // it runs insert query
        if ($qryInsert->execute() === true)
        {
            // it gets the last id genereated by auto insert from DB and update object with this id
            $request->setRequestId(mysqli_insert_id($db));
        }
        else
        {
            // log error
            error_log(date('DATE_ATOM') . ' - Insert request Error ', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryInsert->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Insert not perfomed. ');
        }

        // insert request_items
        if (count($request->getRequestItems()) > 0)
        {
            foreach ($request->getRequestItems() as $requestItem)
            {
                $requestItem->setRequestId($request->getRequestId()); // associa o item ao request rec���m criado
                MdlRequests::insertRequestItem($requestItem);
            }
        }

        return $request;
    }

    // *******************************************************************************************************
    // *** updateRequest
    // *******************************************************************************************************
    // ***
    // ***
    // ***
    public static function updateRequest(Request $request)
    {
        // get instance from DB
        $db = Db::getInstance();

        // it creates UPDATE Query
        $qryUpdate = $db->prepare(' UPDATE request SET req_type=?,req_date=?, ' . ' user_id_req=?,user_id_donor=?,status=? WHERE request_id=?');

        // add values to the query
        $qryUpdate->bind_param('isiiii', $request->getRequestType() , $request->getRequestDate() , $request->getUserIdReq() , $request->getUserIdDonor() , $request->getStatusRequest() , $request->getRequestId());
        // it runs UPDATE query
        if ($qryUpdate->execute() === false)
        {

            // if Error it generates a Log
            error_log(date('DATE_ATOM') . ' - Fail Request Update', 3, getenv('LOG_FILE'));
            error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

            throw new exception('Data Base Fails. Update not perfomed. ');
        }

        // NEVER updates requestItems. Delete'em all and insert again
        MdlRequests::deleteRequestItemByRequestId($request->getRequestId());
        if (count($request->getRequestItems()) > 0)
        {
            foreach ($request->getRequestItems() as $requestItem)
            {
                $requestItem->setRequestId($request->getRequestId()); // associa o item ao request
                MdlRequests::insertRequestItem($requestItem);
            }
        }
    }

    // *******************************************************************************************************
    // *** listRequests
    // *******************************************************************************************************
    // ***
    // *** It gets a array of requests based on given criterias
    // *** it is possible to limit it in order to help display layout
    // ***
    public static function listRequests($crit = null, $limit = null)
    {
        // get instance from DB
        $db = Db::getInstance();

        // request array creation
        $requestList = array();

        $query = 'select r.request_id, r.req_type, r.req_date, r.user_id_req, r.user_id_donor, r.price, r.status ' .
                 '  from request r, request_items ri, city c, user_s4u u' .
                 ' where ri.request_id = r.request_id AND c.city_id = u.city_id AND u.user_id = r.user_id_req ' . (is_null($crit) ? '' : 'and ' . $crit);

        $query .= ' group by r.request_id, r.req_type, r.req_date, r.user_id_req, r.user_id_donor, r.price, r.status ';


        if (!is_null($limit))
        {
            $query .= ' LIMIT ' . $limit;
        }

    //    echo $query;

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
        while ($request = $result->fetch_assoc())
        {
#           $nameReq   = "";
#           $nameDonor = "";

           $requestData = new Request();

#            if($request['user_id_req'] != NULL){
#              $userReq = new User();
#              $userReq = MdlUsers::findUser($request['user_id_req']);
#              $nameReq = $userReq->getFirstName();
#              $emailReq = $userReq->getEmail();

#              $userCity = new City();
#              $userCity = MdlCities::findCity($userReq->getCityId());
#              $cityReq = $userCity-> getCityName();
#            }

#           if($request['user_id_donor'] != NULL){
#             $userDonor = new User();
#             $userDonor = MdlUsers::findUser($request['user_id_donor']);
#             $nameDonor = $userDonor->getFirstName();
#           }

            $requestData->setRequestId($request['request_id']);
            $requestData->setRequestType($request['req_type']);
            $requestData->setRequestDate($request['req_date']);
            $requestData->setUserIdReq($request['user_id_req']);
            $requestData->setPrice($request['price']);
#            $requestData->setUserNameReq($nameReq);
#            $requestData->setUserNameDonor($nameDonor);
#            $requestData->setUserEmailReq($emailReq);
#            $requestData->setUserCityReq($cityReq);
#            $requestData->setUserIdDonor($request['user_id_donor']);
            $requestData->setStatusRequest($request['status']);

            // retrieve requestItem
            $requestItemList = MdlRequests::listRequestItems(' request_id = ' . $request['request_id']);
            if ($requestItemList == NULL)
            {
                $requestItemList = array();
            }
            $requestData->setRequestItems($requestItemList);

            //The array_push() function inserts one or more elements to the end of an array.
            array_push($requestList, $requestData);

        }

        return $requestList;
    }

    // *******************************************************************************************************
    // *** find request
    // *******************************************************************************************************
    // ***
    // *** Return request based on request_id
    // ***
    public static function findRequest($requestId)
    {
        return (MdlRequests::listRequests('r.request_id = ' . $requestId) [0]);
    }

    // *******************************************************************************************************
    // *** find requests
    // *******************************************************************************************************
    // ***
    // *** Return request based on request_id
    // ***

    public static function listDogRequests($bestTime)
    {
        return (MdlRequests::listRequests('type_time = ' . MdlRequests::DOG . ' and best_time = ' . $bestTime));
    }

    public static function listGameRequests($criteria)
    {
        return (MdlRequests::listRequests('type_time = ' . MdlRequests::GAME . ' AND ' . $criteria));
    }
    public static function listPhoneCallRequests($criteria)
    {
        return (MdlRequests::listRequests('type_time = ' . MdlRequests::TALK . ' AND ' . $criteria));
    }
    public static function listWalkDogRequests($criteria)
    {
        return (MdlRequests::listRequests('type_time = ' . MdlRequests::DOG . ' AND ' . $criteria));
    }
    public static function listGroceryRequests($criteria)
    {
        return (MdlRequests::listRequests('req_type = ' . MdlRequests::GROCERY_REQUEST . ' AND ' . $criteria));
    }

    // *******************************************************************************************************
    // *** find User requests
    // *******************************************************************************************************
    // ***
    // *** Return array of request based on $userIdReq
    // ***
    public static function findUserRequest($userIdReq)
    {
        return (MdlRequests::listRequests('user_id_req = ' . $userIdReq) [0]);
    }

// *******************************************************************************************************
// *** translate Best Time
// *******************************************************************************************************
// ***
// *** Return array of request based on $userIdReq
// ***
    public static function translateBestTime($bestTime)
    {
        $bestTimeDescription = ' Anytime '; // default color
        switch ($bestTime) {
            case MdlRequests::MORNING : $bestTimeDescription = ' Morning';
                break;
            case MdlRequests::AFTERNOON : $bestTimeDescription = ' Afternoon';
                break;
            case MdlRequests::NIGHT : $bestTimeDescription = ' Night';
                break;
        }
        return $bestTimeDescription;
    }

  public static function confirmDonation($requestId, $userIdDonor, $status)
  { echo 'request ID ' . $requestId . '</BR>' ;
    echo 'User ID ' . $userIdDonor. '</BR> ';
    echo 'Status ' . $status.  '</BR>' ;
    echo 'Usuario ' . $_SESSION['NAME_USER'] .'</BR>' ;
      // get instance from DB
      $db = Db::getInstance();

      // it creates UPDATE Query
      $qryUpdate = $db->prepare(' UPDATE request SET user_id_donor=?, status=? WHERE request_id=?');

      // add values to the query
      $qryUpdate->bind_param('iii', $userIdDonor , $status , $requestId);



      // it runs UPDATE query
      if ($qryUpdate->execute() === false)
      {
          // if Error it generates a Log
          error_log(date('DATE_ATOM') . ' - Fail Request Update', 3, getenv('LOG_FILE'));
          error_log('                         - erro: ' . $qryUpdate->error_list, 3, getenv('LOG_FILE'));

          throw new exception('Data Base Fails. Update not perfomed. ');
      }

  }

}


// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Request Item
// ***
class RequestItem
{
    private $itemsId;
    private $requestId;
    private $bestTime;
    private $typeTime;
    private $langId;
    private $langName;
    private $item;
    private $phone;
    private $gameId;
    private $gameName;
    private $unitId;
    private $quantity;

    //get and setters
    //Items ID
    public function getItemsId() //Primary Key

    {
        return $this->itemsId;
    }
    public function setItemsId($itemsId)
    {
        $this->itemsId = $itemsId;
    }
    //Request ID - FK from Request Table
    public function getRequestId()
    {
        return $this->requestId;
    }
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    // Best Time to receive the donation (1-morning, 2- Afternoon, 3-Night, 4 - Anytime)
    public function getBestTime()
    {
        return $this->bestTime;
    }
    public function setBestTime($bestTime)
    {
        $this->bestTime = $bestTime;
    }

    //Type Time ( 1 - Time Talk, 2 - Time Game, 3 - Time Dog, 4 - Time Other)
    public function getTypeTime()
    {
        return $this->typeTime;
    }
    public function setTypeTime($typeTime)
    {
        $this->typeTime = $typeTime;
    }

    //Language ID FK from Language table
    public function getLangId()
    {
        return $this->langId;
    }
    public function setLangId($langId)
    {
        $this->langId = $langId;
    }
    //Language Name from Language table
    public function getLangName()
    {
        return $this->langName;
    }
    public function setLangName($langName)
    {
        $this->langName = $langName;
    }
    //Item requested
    public function getItem()
    {
        return $this->item;
    }
    public function setItem($item)
    {
        $this->item = $item;
    }

    //Phone number from the person who whants to talk
    public function getPhone()
    {
        return $this->phone;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    // Game ID FK from Game_Type (it is the plataform: PS4, PC, Xbox, etc)
    public function getGameId()
    {
        return $this->gameId;
    }
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
    }

    //Game Name
    public function getGameName()
    {
        return $this->gameName;
    }
    public function setGameName($gameName)
    {
        $this->gameName = $gameName;
    }

    //Unit ID Fk from table Unit (exemples 1 - Liter, 2 - Kilo, 3 - Grams, 4 - Unit, 5 - Box)
    public function getUnitId()
    {
        return $this->unitId;
    }
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    //Quantity requested
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}

// *******************************************************************************************************
// *** Class
// *******************************************************************************************************
// ***
// *** Defines Request
// ***
//****************************************
class Request
{
    private $requestId;
    private $reqType;
    private $reqDate;
    private $userIdReq;
#    private $userNameReq;
#    private $userEmailReq;
#    private $userCityReq;
    private $userIdDonor;
    private $price;
#    private $userNameDonor;
    private $status;
    private $requestItems = array();

    //****************************************
    // getters e setters
    //Request ID
    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    // Request type (1-Grocery, 2-Time)
    public function getRequestType()
    {
        return $this->requestType;
    }

    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
    }
    // Request Date
    public function getRequestDate()
    {
        return $this->requestDate;
    }
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;
    }
    // User that is requiring Help
    public function getUserIdReq()
    {
        return $this->userIdReq;
    }
    public function setUserIdReq($userIdReq)
    {
        $this->userIdReq = $userIdReq;
    }
    //User that is donationg
    public function getUserIdDonor()
    {
        return $this->userIdDonor;
    }
    public function setUserIdDonor($userIdDonor)
    {
        $this->userIdDonor = $userIdDonor;
    }
    // Request Status (1 - Aactive, 2 - in progress, 3- ended)
    public function getStatusRequest()
    {
        return $this->statusRequest;
    }
    public function setStatusRequest($statusRequest)
    {
        $this->statusRequest = $statusRequest;
    }

    public function getRequestItems()
    {
        return $this->requestItems;
    }
    public function setRequestItems($requestItems)
    {
        $this->requestItems = $requestItems;
    }
#    // First User that is requiring Help
#    public function getUserNameReq()
#    {
#        return $this->userNameReq;
#    }
#    public function setUserNameReq($userNameReq)
#    {
#        $this->userNameReq = $userNameReq;
#    }
#    // Email User that is requiring Help
#    public function getUserEmailReq()
#    {
#        return $this->userEmailReq;
#    }
#    public function setUserEmailReq($userEmailReq)
#    {
#        $this->userEmailReq = $userEmailReq;
#    }
#    // Name Donor
#    public function getUserNameDonor()
#    {
#        return $this->userNameDonor;
#    }
#    public function setUserNameDonor($userNameDonor)
#    {
#        $this->userNameDonor = $userNameDonor;
#    }
#    public function getUserCityReq()
#    {
#        return $this->userCityReq;
#    }
#    public function setUserCityReq($userCityReq)
#    {
#        $this->userCityReq = $userCityReq;
#    }
    //Request price
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }

    //Request Itens
    public function addRequestItem(RequestItem $requestItem)
    {
        $requestItem->setRequestId($this->requestId); // para garantir que os objetos estejam associados
        array_push($this->requestItems, $requestItem);
    }

}
