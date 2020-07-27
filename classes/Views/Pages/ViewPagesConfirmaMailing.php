<?php
// Boiler
// ViewPagesConfirmaMailing.php: definiçao da view de aviso de manutenção
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma tela com a mensagem configurada no banco e uma opção para cadastramento no mailing da Boiler 
class ViewPagesConfirmaMailing 
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model) 
    {
        $this->controllerModel = $model;
    }
    
    public function output() 
    {
        $output .= file_get_contents('classes/Views/Pages/confirmaMailing.html');        
        $output .= file_get_contents('classes/Views/Pages/footerHome.html');

        // substituição de variáveis 
        $output = str_replace('{versao}', getenv('VER'), $output);
        $output = str_replace('{boilerTextoLandingPage}', $this->controllerModel->typeform->getTextoLandingPage(), $output);
        $output = str_replace('{boilerNomeMailing}', $this->controllerModel->mailing->getNomeAtleta(), $output);
        $output = str_replace('{boilerEmailMailing}', $this->controllerModel->mailing->getEmail(), $output);

        return $output;
    }
}
