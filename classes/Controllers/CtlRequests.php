<?php
// Support for you
// CtlRequests.php: controller of Requests
// autor: Vinicius Alves
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
      $this->view = new ViewNewGroceryRequest($this->model);
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
        $this->model->request->setUserIdReq($_SESSION['ID_USUARIO']);
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
            $this->model->requestItem->setBestTime($_POST['bestTime']);
            $this->model->requestItem->setGameId($_POST['gameId']);
            $this->model->requestItem->setGameName($_POST['gameName']);
            $this->model->requestItem->setLangId($_POST['langId']);
            $this->model->requestItem->setPhone($_POST['phone']);

            $this->model->request->addRequestItem($this->model->requestItem);
        }

        MdlRequests::insertRequest($this->model->request);

        // define the view
        $this->view = new ViewSuccessReq();
    }

        public function newPlayDonation()
        {
          $this->view = new ViewPlayGameDonation($this->model);
        }

        public function newDogDonation($bestTime)
        {
          if($bestTime===NULL)
          {
            $this->view = new ViewWalkDogDonation($this->model);
          } else
          {
            $reqDogs = array();
            $crit = 'request_id = ' . $requestId
            $reqDogs = MdlRequests::listRequest($this->model->$crit);
            $this->view = new ViewWalkDogDonation($this->model);
            return $reqDods;
          }
        }
}
