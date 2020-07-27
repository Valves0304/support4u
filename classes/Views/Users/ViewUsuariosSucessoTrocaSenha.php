<?php
// Boiler
// ViewUsuariosSucessoTrocaSenha.php: definiçao da view de sucesso da troca de senha
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma tela de sucesso na troca da senha
class ViewUsuariosSucessoTrocaSenha
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model) 
    {
        $this->controllerModel = $model;
    }
    
    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= file_get_contents('classes/Views/htmlTemplates/sucessoTrocaSenha.html');        
        $output .= file_get_contents('classes/Views/htmlTemplates/footerSemScrolling.html');

        // substituição de variáveis
        $output = str_replace('{versao}', getenv('VER'), $output);

        return $output;
    }
}
