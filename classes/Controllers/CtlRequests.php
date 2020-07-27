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

    public function registerTimeRequest()
    {
        // get parameters
        $idUser     = $_SESSION['ID_USUARIO'];

        if ($iUser === NULL) {
            throw new Exception('Usuer not loged in');
        }
        //Check Type Time requested
        //( 1 - Time Talk, 2 - Time Game, 3 - Time Dog, 4 - Time Other)

    }


    // create view to add a new request
    public function newGroceryRequest()
    {
      $this->view = new ViewNewGroceryRequest($this->model);
    }
    public function findRequest()
    {
      $this->view = new ViewFindRequest($this->model);
    }
    public function insertRequest()
    {
      $this->model->request = new Request();
      $this->model->requestItem = new RequestItem();

        // sets the Request object with form input
        $this->model->request->setRequestType($_POST['reqType']);
        $this->model->request->setRequestDate($_POST['reqDate']);
        $this->model->request->setUserIdReq($_SESSION['ID_USUARIO']);
        $this->model->request->setUserIdDonor($_POST['userIdDonor']);
        $this->model->request->setStatusRequest($_POST['status']);

        $this->model->requestItem->setBestTime($_POST['bestTime']);
        $this->model->requestItem->setGameId($_POST['gameId']);
        $this->model->requestItem->setGameName($_POST['gameName']);
        $this->model->requestItem->setItem($_POST['item']);
        $this->model->requestItem->setLangId($_POST['langId']);
        $this->model->requestItem->setPhone($_POST['phone']);
        $this->model->requestItem->setQuantity($_POST['quantity']);
        $this->model->requestItem->setRequestId($_POST['$requestId']);
        $this->model->requestItem->setTypeTime($_POST['typeTime']);
        $this->model->requestItem->setUnitId($_POST['unitId']);

        MdlRequests::insertRequestItem($this->model->requestItem);
        MdlRequests::insertRequest($this->model->request);

            // define the view
            $this->view = new ViewPagesHome();
        }
    }
