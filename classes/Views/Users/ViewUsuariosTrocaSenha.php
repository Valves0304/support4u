<?php
// Boiler
// ViewUsuariosTrocaSenha.php: definiçao da view de troca senha de usuário
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe um formulário para a realização da troca de senha
class ViewUsuariosTrocaSenha 
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model) 
    {
        $this->controllerModel = $model;
    }
    
    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= file_get_contents('classes/Views/Usuarios/trocaSenha.html');        
        $output .= file_get_contents('classes/Views/Pages/footer.html');
        
        // substituição de variáveis
        if (!is_null($this->controllerModel->campoErro)) {
            $output = str_replace('{estilo_erro}', 'has-error', $output);
            $output = str_replace('{mensagem_erro}', $this->controllerModel->msgErro, $output);
        } else {
            $output = str_replace('{estilo_erro}', '', $output);
            $output = str_replace('{mensagem_erro}', '', $output);
        }
        
        $output = str_replace('{versao}', getenv('VER'), $output);
        return $output;
    }
}
