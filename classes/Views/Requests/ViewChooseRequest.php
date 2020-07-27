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
        $output .= file_get_contents('classes/Views/Requests/chooseRequestButtons.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement

        $output = str_replace('{s4uRequestGrocery}',"''",$output);
        $output = str_replace('{s4uRequestTalk}',"''",$output);
        $output = str_replace('{s4uRequestDog}',"''",$output);
        $output = str_replace('{s4uRequestGame}',"''",$output);
        $output = str_replace('{s4uRequestOther}',"''",$output);

//test

        $output = str_replace('{s4uRequestTest}', Util::createLink("CtlRequests","newGroceryRequest"), $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
