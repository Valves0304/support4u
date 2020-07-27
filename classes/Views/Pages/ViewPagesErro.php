<?php
// Boiler
// ViewPagesErro.php: definiçao da view de Erro do controller cPages
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe uma tela de erro 
class ViewPagesErro {
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model) 
    {
        $this->controllerModel = $model;
    }
    
    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= '<div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-3 col-sm-offset-1">
                          <span class="glyphicon glyphicon glyphicon-thumbs-down logo red-boiler"></span>
                        </div>
                        <div class="col-sm-8 texto-erro">
                          <h4>OOPS! Algo de errado aconteceu:</h4>';
        $output .= '      <i>' . $this->controllerModel->codErro . ' - ' . $this->controllerModel->msgErro . '</i>';
        $output .= '      <div class="red-boiler"><BR><a href=boiler.php?c=CtlPages&action=home>Voltar para home</a></div>';
        $output .= '</div></div></div>';
        $output .= file_get_contents('classes/Views/Pages/footer.html');
        
        $output = str_replace('{versao}', getenv('VER'), $output);
        
        return $output;
    }
}
