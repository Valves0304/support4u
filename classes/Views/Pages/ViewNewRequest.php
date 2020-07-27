<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewNewRequest
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output .= file_get_contents('classes/Views/Pages/header.html');
        $output .= file_get_contents('classes/Views/Pages/newRequest.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output);
        return $output;
    }
}
