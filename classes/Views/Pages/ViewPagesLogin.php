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
        //$output  = ViewUserHeaderHTML::output();
        $output  = file_get_contents('classes/Views/Pages/header.html');
        $output .= file_get_contents('classes/Views/Pages/login.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');


        // substituição de variáveis
        if (!is_null($this->controllerModel->codError)) {
            $output = str_replace('{estilo_erro}', 'has-error', $output);
            $output = str_replace('{mensagem_erro}', $this->controllerModel->msgError, $output);
        } else {
            $output = str_replace('{estilo_erro}', '', $output);
            $output = str_replace('{mensagem_erro}', '', $output);
        }

        $output = str_replace('{versao}', getenv('VER'), $output);
        return $output;
    }
}
