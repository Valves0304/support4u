<?php
// Support for you
// CtlRequests.php: controller of Requests
// author: Vinicius Alves
// ---------------------------------------------------------------------------
// - Controller actions:
// - Home - Home Page load
// - About - Load About Page
// - Error (message) - display Error Page with error message
// -> this controller has a basic model for strict observance of the MVC pattern
class CtlRequests
{
    // Attributes
    public $view;   // controller chosen view
    private $model; // controller model


    // CtlPages Construtor
    public function __construct()
    {
        $this->model = new MdlRequests();
    }

    // methods (actions)
    public function home()
    {
            //default function, the view is already defined by constructor
    }

    public function getStarted()
    { //It goes to two main buttons (request donate)
        $this->view = new ViewGetStarted();
    }

    public function chooseRequest()
    { //It goes Requst options Buttons
        $this->view = new ViewChooseRequest();
    }

    public function chooseDonation()
    { //It goes Donation options Buttons
        $this->view = new ViewChooseDonation();
    }

    public function findRequest()
    {
      $this->view = new ViewFindRequest($this->model);
    }

    // create view to add a new request
    public function newGroceryRequest()
    {
      $this->view = new ViewGroceryRequest($this->model);
    }

    public function newDogRequest()
    {
      $this->view = new ViewWalkDog($this->model);
    }

    public function newPhoneRequest()
    {
      $this->view = new ViewPhoneCall($this->model);
    }
    public function newPlayRequest()
    {
      $this->view = new ViewPlayGame($this->model);
    }

    public function insertRequest()
    {
        // sets the Request object with form input
        $this->model->request = new Request();
        $this->model->request->setRequestType($_GET['reqType']);
        $this->model->request->setRequestDate(date("Y/m/d"));
        $this->model->request->setUserIdReq($_SESSION['ID_USER']);
        $price = isset($_POST["price"]) ? $_POST['price'] : 0;
        $this->model->request->setPrice($price);
        $this->model->request->setStatusRequest('1'); // we are creating the request so Status is always 1

        // sets the Request Item(s), depending on reqType
        if ($_GET['reqType'] == MdlRequests::GROCERY_REQUEST) {
            for ($i = 0; $i < 10; $i++) {
                if ($_POST['item'][$i] != '') {
                    $requestItem = new RequestItem();
                    $requestItem->setItem($_POST['item'][$i]);
                    $requestItem->setQuantity($_POST['qty'][$i]);
                    $requestItem->setUnitId($_POST['unit'][$i]);
                    $this->model->request->addRequestItem($requestItem);
                }
            }

        } else { // time request
            $this->model->requestItem = new RequestItem();
            $this->model->requestItem->setTypeTime($_GET['typeTime']);
            $this->model->requestItem->setBestTime(isset($_POST['bestTime']) ? $_POST['bestTime'] : 4);
            $this->model->requestItem->setGameId(isset($_POST['gameId']) ? $_POST['gameId'] : 0);
            $this->model->requestItem->setGameName(isset($_POST['gameName']) ? $_POST['gameName'] : 0);
            $this->model->requestItem->setLangId(isset($_POST['langId']) ? $_POST['langId'] : 0);
            $this->model->requestItem->setPhone(isset($_POST['phone']) ? $_POST['phone'] : 0);

            $this->model->request->addRequestItem($this->model->requestItem);
        }

        // validates form input
        if ($this->requestFormValidation($this->model->request)) {

            // error found. Return to user form and display error message
            // View depends on reqType and typeTime
            if ($_GET['reqType'] == MdlRequests::GROCERY_REQUEST) {
                $this->view = new ViewGroceryRequest($this->model);
            } elseif ($_GET['typeTime'] == MdlRequests::TALK) {
                $this->view = new ViewPhoneCall($this->model);
            } elseif ($_GET['typeTime'] == MdlRequests::DOG) {
                $this->view = new ViewWalkDog($this->model);
            } elseif ($_GET['typeTime'] == MdlRequests::GAME) {
                $this->view = new ViewPlayGame($this->model);
            } else {
                $this->view = new ViewPhoneCall($this->model);
            }

            return;
        }

        // validation was successful!
        MdlRequests::insertRequest($this->model->request);

        // define the view
        $this->view = new ViewSuccessReq();
    }


    // *******************************************************************************************************
    // *** requestFormValidation
    // *******************************************************************************************************
    // - PRIVATE method, should be called only within Class' scope
    // - MUST receive valid request object
    // - Sets Model's error field and message
    // - Returns on the first error found
    // - Returns 1 on error, 0 on success
    private function requestFormValidation($request)
    {
        $this->model->errorField = null;
        $this->model->errorMsg = null;

        // Validation depends on request type
        if ($request->getRequestType() == MdlRequests::GROCERY_REQUEST) {

            if ($request->getRequestItems()[0]->getItem() == '' or $request->getRequestItems()[0]->getItem() === NULL) {
                $this->model->errorField = 1;
                $this->model->errorMsg = "Must inform at least 1 item description";
                return 1;
            }

        } elseif ($request->getRequestItems()[0]->getTypeTime() == MdlRequests::TALK) {

            if ($request->getRequestItems()[0]->getLangId() == 0 or $request->getRequestItems()[0]->getLangId() === NULL) {
                $this->model->errorField = 1;
                $this->model->errorMsg = "Please select one language";
                return 1;
            }

            if ($request->getRequestItems()[0]->getPhone() == '' or $request->getRequestItems()[0]->getPhone() === NULL) {
                $this->model->errorField = 2;
                $this->model->errorMsg = "Must inform phone number";
                return 1;
            }

        } elseif ($request->getRequestItems()[0]->getTypeTime() == MdlRequests::GAME) {  // to do

            if ($request->getRequestItems()[0]->getGameId() == 0 or $request->getRequestItems()[0]->getGameId() === NULL) {
                $this->model->errorField = 1;
                $this->model->errorMsg = "Please select one platform";
                return 1;
            }

            if ($request->getRequestItems()[0]->getGameName() == '' or $request->getRequestItems()[0]->getGameName() === NULL) {
                $this->model->errorField = 2;
                $this->model->errorMsg = "Must inform game name";
                return 1;
            }

        }

        // No errors
        return 0;
    }

    public function newPlayGameDonation()
    {

        $gameId = isset($_POST['gameId']) ? $_POST['gameId'] : 0;
        $bestTime = isset($_POST['bestTime']) ? $_POST['bestTime'] : 4;

        $criteria = ' 1 = 1 ';
        $criteria .= $gameId > 0 ? ' AND game_id = ' . $gameId : '';

        //If you can donate anytime so this time resticition is not necessary
        if ($bestTime != 4){
          $criteria .= ' AND ( best_time = 4 or best_time = ' . $bestTime . ' )';
        }

        $criteria .= ' AND status = ' . MdlRequests::ACTIVE_REQUEST;

        $this->model->requestList = MdlRequests::listGameRequests($criteria);
        $this->view = new ViewPlayGameDonation($this->model);

    }

    public function matchPlayGame()
    {
        $request = isset($_POST["optionRequest"]) ? $_POST['optionRequest'] : 0;
        if($request != 0){
          $this->model->request = MdlRequests::findRequest($request);
          $this->view = new ViewPlayGameMatch($this->model);
        } else{

        $this->view = new ViewPlayGameDonation($this->model);

        }

    }

    public function newPhoneCallDonation()
    {
        $loginView = CtlUSers::checkUserSession();
        if(isset($loginView)){
            $this->view = $loginView;
            return;
        }

          $languageId = isset($_POST['languageId']) ? $_POST['languageId'] : 1;
          $bestTime = isset($_POST['bestTime']) ? $_POST['bestTime'] : 4;

          $criteria = ' 1 = 1 ';

          //If you can donate any language so this language resticition is not necessary
          if ($languageId !=0 ){
            $criteria .= ' AND lang_id = ' . $languageId . '';
          }

          //If you can donate anytime so this time resticition is not necessary
          if ($bestTime != 4){
            $criteria .= ' AND ( best_time = 4 or best_time = ' . $bestTime . ' )';
          }

          $criteria .= ' AND status = ' . MdlRequests::ACTIVE_REQUEST;

          $this->model->requestList = MdlRequests::listPhoneCallRequests($criteria);
          $this->view = new ViewPhoneCallDonation($this->model);

      }

    public function matchPhoneCall()
    {
        $request = isset($_POST["optionRequest"]) ? $_POST['optionRequest'] : 0;
        if($request != 0){
            $this->model->request = MdlRequests::findRequest($request);
            $this->view = new ViewPhoneCallMatch($this->model);
        }else{
            $this->view = new ViewPhoneCallDonation($this->model);
        }
    }

       public function matchDonation()
       {
         MdlRequests::confirmDonation($_GET["reqId"],$_SESSION['ID_USUARIO'],2);
        $this->view = new ViewPagesHome($this->model);

        }
        public function newWalkDogDonation()
        {

            $cityId = isset($_POST['cityId']) ? $_POST['cityId'] : 0;
            $bestTime = isset($_POST['bestTime']) ? $_POST['bestTime'] : 4;

            $criteria = ' 1 = 1 ';
            $criteria .= $cityId > 0 ? ' AND c.city_id = ' . $cityId : '';

            //If you can donate anytime so this time resticition is not necessary
            if ($bestTime != 4){
              $criteria .= ' AND ( best_time = 4 or best_time = ' . $bestTime . ' )';
            }

            $criteria .= ' AND status = ' . MdlRequests::ACTIVE_REQUEST;

            $this->model->requestList = MdlRequests::listWalkDogRequests($criteria);
            $this->view = new ViewWalkDogDonation($this->model);

        }

        public function matchWalkDog()
        {
          $request = isset($_POST["optionRequest"]) ? $_POST['optionRequest'] : 0;
          if ($request != 0){
            $this->model->request = MdlRequests::findRequest($request);
            $this->view = new ViewWalkDogMatch($this->model);
          } else{
            $this->view = new ViewWalkDogDonation($this->model);
          }
        }

          public function newGroceryDonation()
          {

              $cityId = isset($_POST['cityId']) ? $_POST['cityId'] : 0;
              $price = isset($_POST['price']) ? $_POST['price'] : 1000;


              $criteria = ' 1 = 1 ';
              $criteria .= $cityId > 0 ? ' AND c.city_id = ' . $cityId : '';

            if ($price <> '') {
              $criteria .= ' AND r.price <= ' . $price;
            }

              $criteria .= ' AND status = ' . MdlRequests::ACTIVE_REQUEST;

              $this->model->requestList = MdlRequests::listGroceryRequests($criteria);
              $this->view = new ViewGroceryDonation($this->model);

          }
        public function matchGrocery()
        {
            $request = isset($_POST["optionRequest"]) ? $_POST['optionRequest'] : 0;

            if ($request !=0){
                $this->model->request = MdlRequests::findRequest($request);
                $this->view = new ViewGroceryMatch($this->model);
            } else {
                $this->view = new ViewGroceryDonation($this->model);
            }
        }
}
