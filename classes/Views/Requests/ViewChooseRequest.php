<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewChooseRequest
{
    // ViewPagesHome Construtor
    public function __construct()
    {

    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/chooseRequestButtons.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement

        $output = str_replace('{s4uRequestGrocery}', Util::createLink("CtlRequests","newGroceryRequest"),$output);
        $output = str_replace('{s4uReqPhoneCall}', Util::createLink("CtlRequests","newPhoneRequest"), $output);
        $output = str_replace('{s4uReqWalkDog}',   Util::createLink("CtlRequests","newDogRequest"), $output);
        $output = str_replace('{s4uReqPlayGame}',  Util::createLink("CtlRequests","newPlayRequest"), $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
