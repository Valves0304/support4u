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
        $this->view = new ViewNewRequest();
        $this->model = new MdlRequests();
    }

    // methods (actions)
    public function home()
    {

            //default function, the view is already defined by constructor
    }

    public function getStarted()
    {
        $this->view = new ViewGetStarted();
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

        // create view to add a new request

        //$this->view = new ViewTimeRequest($this->model);
    }


}
