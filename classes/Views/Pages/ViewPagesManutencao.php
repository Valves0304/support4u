<?php
// Boiler
// ViewPagesManutenção.php: definiçao da view de aviso de manutenção
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma tela avisando a manutenção do site 
class ViewPagesManutencao {

    public function output() 
    {
        $output .= file_get_contents('classes/Views/Pages/home.html');        
        $output .= file_get_contents('classes/Views/Pages/footerHome.html');

        // substituição de variáveis 
        $output = str_replace('{versao}', getenv('VER'), $output);
        $output = str_replace('{boilerMsgHome}', '... está em manutenção mas já volta ;)', $output);

        return $output;
    }
}
