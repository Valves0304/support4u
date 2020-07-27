<?php
// S4u
// ViewPagesError.php: View Errors from controller ctlPages
// autor: Vinicius Alves
// ---------------------------------------------------------------------------
// Error screen
class ViewPagesErro {
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model)
    {
        $this->controllerModel = $model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= '<div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-3 col-sm-offset-1">
                          <span class="glyphicon glyphicon glyphicon-thumbs-down logo red-boiler"></span>
                        </div>
                        <div class="col-sm-8 texto-erro">
                          <h4>OOPS! Algo de errado aconteceu:</h4>';
        $output .= '      <i>' . $this->controllerModel->codErro . ' - ' . $this->controllerModel->msgErro . '</i>';
        $output .= '      <div class="red-s4u"><BR><a href=s4u.php?c=CtlPages&action=home>Back to HomePage</a></div>';
        $output .= '</div></div></div>';
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        $output = str_replace('{versao}', getenv('VER'), $output);

        return $output;
    }
}
