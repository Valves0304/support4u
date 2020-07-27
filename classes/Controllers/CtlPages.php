<?php
// Support for you
// CtlPages.php: controller of user's site browsing
// autor: Vinicius Alves
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
            $ctl->login($_COOKIE['boilerchannel']); // aqui era a chamada que te confundiu, passando o login via parâmetro
            $this->view = $ctl->view;

        } else {

            // aqui ele coloca a mensagem de erro e campo que ele recebeu,
            // porque ele pode estar chamando esta ação pela segunda vez, em caso de erro,
            // e quer exibir a mensagem na tela
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
        // modifica o modelo para que a view o consulte diretamente
        $this->model->msgErro = $msgErro;
        $this->model->codErro = $codErro;
        $this->view = new ViewPagesErro($this->model);
    }

}
