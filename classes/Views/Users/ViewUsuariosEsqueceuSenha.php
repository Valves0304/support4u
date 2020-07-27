<?php
// Boiler
// ViewUsuariosEsqueceuSenha.php: definiçao da view de envio de nova senha
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma mensagem avisando que um email foi enviado
class ViewUsuariosEsqueceuSenha 
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct() 
    {
    }

    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= file_get_contents('classes/Views/Usuarios/esqueceuSenha.html');        
        $output .= file_get_contents('classes/Views/Pages/footer.html');
        
        $output = str_replace('{versao}', getenv('VER'), $output);
        return $output;
    }
}
