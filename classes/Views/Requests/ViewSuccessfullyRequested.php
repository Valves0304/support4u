<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewSuccessfullyRequested
{
    // ViewPagesHome Construtor
    public function __construct()
    {

    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/successfullyRequested.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uMain}', Util::createLink("CtlPages","home","",""), $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
