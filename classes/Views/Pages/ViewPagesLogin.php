<?php
// Boiler
// ViewPagesLogin.php: definiçao da view de login do controller cEventos
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe um formulário para a realização de login
class ViewPagesLogin
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model)
    {
        $this->controllerModel = $model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Pages/login.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');


        // variables replacement
        if (!is_null($this->controllerModel->codError)) {
            $output = str_replace('{estilo_erro}', 'has-error', $output);
            $output = str_replace('{s4uErrorMesage}', $this->controllerModel->msgError, $output);
        } else {
            $output = str_replace('{estilo_erro}', '', $output);
            $output = str_replace('{s4uErrorMesage}', '', $output);
        }
        $output = str_replace('{s4uLogin}', Util::createLink("CtlUsers","login"), $output);
        $output = str_replace('{s4uRegister}', Util::createLink("CtlUsers","userRegister"), $output);
        $output = str_replace('{version}', getenv('VER'), $output);
        return $output;
    }
}
