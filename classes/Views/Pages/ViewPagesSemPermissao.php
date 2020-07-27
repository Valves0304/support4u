<?php
// Boiler
// ViewPagesSemPermissao.php: definiçao da view de aviso de falta de permissão
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma tela avisando que o usuario não tem permissão para a ação desejada 
class ViewPagesSemPermissao {

    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= '<div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-3 col-sm-offset-1">
                          <span class="fa fa-2x fa-ban logo red-boiler"></span>
                        </div>
                        <div class="col-sm-8 texto-erro">
                          <h1>Sem Permissão:</h1>';
        $output .= '<h4>Você não possui a permissão necessária para esta ação.<BR>' . 
                        'Entre em contato com o <a href=mailto:admin@boilerchannel.com?Subject=Sem%20Permissao>Administrador do sistema</a>.<BR>';
        $output .= '      <div class="red-boiler"><BR><a href=http://wodsunset.com.br>WOD Sunset</a><BR>ou <a href=boiler.php?c=CtlPages&action=home>Voltar para home</a></div>';
        $output .= '</div></div></div>';
        $output .= file_get_contents('classes/Views/htmlTemplates/footerSemScrolling.html');
        
        $output = str_replace('{versao}', getenv('VER'), $output);
        
        return $output;
    }
}
