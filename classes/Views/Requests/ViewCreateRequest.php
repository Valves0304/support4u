<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewUserRegister
{
    private $controllerModel;   // model modified by controller

    // ViewUserRegister Construtor
    public function __construct($model)
    {
      $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/createRequest.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output);

        // tag <SELECT> for Cities

        return $output;
    }
}
