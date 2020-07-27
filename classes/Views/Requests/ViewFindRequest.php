<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewFindRequest
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/findRequest.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uCreateRequest}', Util::createLink("CtlRequests","findRequest"), $output);

        $output = str_replace('{s4uField}',"",$output);
        $output = str_replace('{s4ureqType}',$request->getRequestType(),$output);
        $output = str_replace('{s4ureqDate}',$request->getRequestDate(),$output);
        $output = str_replace('{s4uuserIdReq}',$request->getUserIdReq(),$output);
        $output = str_replace('{s4uuserIdDonor}',$request->getUserIdDonor(),$output);
        $output = str_replace('{s4ustatus}',$request->getStatusRequest(),$output);
        $output = str_replace('{s4ubestTime}',$requestItem->getBestTime(),$output);
        $output = str_replace('{s4utypeTime}',$requestItem->getTypeTime(),$output);
        $output = str_replace('{s4ulangId}',$requestItem->getLangId(),$output);
        $output = str_replace('{s4uitem}',$requestItem->getItem(),$output);
        $output = str_replace('{s4uphone}',$requestItem->getPhone(),$output);
        $output = str_replace('{s4ugameId}',$requestItem->getGameId(),$output);
        $output = str_replace('{s4ugameName}',$requestItem->getGameName(),$output);
        $output = str_replace('{s4uunitId}',$requestItem->getUnitId(),$output);
        $output = str_replace('{s4uquantity}',$requestItem->getQuantity(),$output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
