<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPagesHome
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewPagesHome";
    }

    public function output()
    {
        $output .= file_get_contents('classes/Views/Pages/header.html');
        $output .= file_get_contents('classes/Views/Pages/home.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
