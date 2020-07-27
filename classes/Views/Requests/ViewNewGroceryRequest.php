<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewNewGroceryRequest
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/createRequest.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uCreateRequest}', Util::createLink("CtlRequests","insertRequest"), $output);

        $output = str_replace('{s4uField}',"",$output);
        $output = str_replace('{s4ureqType}',"",$output);
        $output = str_replace('{s4ureqDate}',"",$output);
        $output = str_replace('{s4uuserIdReq}',"",$output);
        $output = str_replace('{s4uuserIdDonor}',"",$output);
        $output = str_replace('{s4ustatus}',"",$output);
        $output = str_replace('{s4ubestTime}',"",$output);
        $output = str_replace('{s4utypeTime}',"",$output);
        $output = str_replace('{s4ulangId}',"",$output);
        $output = str_replace('{s4uitem}',"",$output);
        $output = str_replace('{s4uphone}',"",$output);
        $output = str_replace('{s4ugameId}',"",$output);
        $output = str_replace('{s4ugameName}',"",$output);
        $output = str_replace('{s4uunitId}',"",$output);
        $output = str_replace('{s4uquantity}',"",$output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
