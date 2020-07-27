<?php
// Support for you
// CtlPages.php: controller of user's site browsing
// author: Vinicius Alves
// ---------------------------------------------------------------------------
// - Controller actions:
// - Home - Home Page load
// - About - Load About Page
// - Error (message) - display Error Page with error message
// -> this controller has a basic model for strict observance of the MVC pattern
class CtlPages
{
    // Attributes
    public $view;   // controller chosen view
    private $model; // controller model

    // CtlPages Construtor
    public function __construct()
    {
        $this->view = new ViewPagesHome();
        $this->model = new MdlPages();
    }

    // methods (actions)
    public function home()
    {

            //default function, the view is already defined by constructor
    }


    public function about()
    {
        $this->view = new ViewPagesAbout();
    }

    public function login($campoErro, $msgErro = null)
    {

        // check cookie
        if(isset($_COOKIE['boilerchannel'])) {

            $ctl = new CtlUsuarios();
            $ctl->login($_COOKIE['boilerchannel']);
            $this->view = $ctl->view;

        } else {

            // here the system inserts the error message and field received,
           // because it may be calling this action a second time, in case of an error,
          // and it may needs to display the message on the screen
            $this->model->msgErro = $msgErro;
            $this->model->campoErro = $campoErro;
            $this->view = new ViewPagesLogin($this->model);
        }

    }

    public function semPermissao()
    {
        $this->view = new ViewPagesSemPermissao();
    }

    public function error($codErro, $msgErro)
    {
        // modify the model so that the view queries it directly
        $this->model->msgErro = $msgErro;
        $this->model->codErro = $codErro;
        $this->view = new ViewPagesErro($this->model);
    }

}
