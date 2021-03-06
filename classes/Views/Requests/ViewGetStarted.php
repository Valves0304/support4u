<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewGetStarted
{
    // ViewPagesHome Construtor
    public function __construct()
    {

    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/getStartedButtons.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement

        $output = str_replace('{s4uChooseRequest}',  Util::createLink("CtlRequests","chooseRequest"), $output);
        $output = str_replace('{s4uChooseDonation}', Util::createLink("CtlRequests","chooseDonation"), $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
