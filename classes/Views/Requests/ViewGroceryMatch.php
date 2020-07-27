<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewGroceryMatch
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }


    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/matchGrocery.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');


        // variables replacement
        $output = str_replace('{s4uConfirmation}', Util::createLink("CtlRequests","matchDonation","","",$GLOBALS['RequestMatch']), $output);
        $output = str_replace('{s4uCancel}',  Util::createLink("CtlRequests","newGroceryDonation"), $output);

        // table with requests
        $tableRequests = '<TABLE class="pure-table pure-table-bordered"><THEAD><TR><TD>Name</TD><TD>Email</TD><TD>Time</TD><TD>City</TD></TR></THEAD> ';
        $tableRequests .= '<TBODY>';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<TR><TD COLSPAN=5>There are no requests. Try changing your filter.</TD></TR> \r\n';
        } else {
            foreach ($this->controllerModel->requestList as $request) {
              $tableRequests .= "\n<TR><TD>"  . $request->getUserNameReq(). "</TD>" .
                                '      <TD>' . $request->getUserEmailReq(). '</TD>' .
                               '      <TD>' . $request->getUserCityReq() . '</TD>' .
                               '      <TD>' . $request->getRequestItems()[0]->getBestTime() . '</TD>' .
                                 '  </TR>';
            }
        }
        $tableRequests .= '</TBODY>';
        $tableRequests .= "\n</TABLE>";

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
