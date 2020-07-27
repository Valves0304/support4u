<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewUserRegister
{
    private $controllerModel;   // model modified by controller

    // ViewUserRegister Construtor
    public function __construct()
    {

    }

    public function output()
    {
        $output = file_get_contents('classes/Views/Pages/header.html');
        $output .= file_get_contents('classes/Views/Users/userRegister.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output); // Waghabi esse nao funciona
        if (isset($_SESSION['NOME_USUARIO'])) {
          $output = str_replace('{msg-usuario}','Hello '. $_SESSION['NOME_USUARIO'] , $output);
        } else {
          $output = str_replace('{msg-usuario}','Login', '<a href=s4u.php?c=CtlPagess&action=login><span class="fa fa-sign-in red-boiler-background"></span> Login</a>', $output);
        }

        return $output;
    }
}
