<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPlayGameMatch
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }


    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/matchPlayGame.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        $name = "";

        // variables replacement
        $output = str_replace('{s4uConfirmation}', Util::createLink("CtlRequests","matchDonation","","",$GLOBALS['RequestMatch']), $output);
        $output = str_replace('{s4uCancel}',  Util::createLink("CtlRequests","newPlayDonation"), $output);



            $tableRequests = '<TABLE class="pure-table pure-table-bordered"><THEAD><TD>Name</TD><TD>Phone</TD><TD>Time</TD><TD>Language</TD></THEAD> ';
            $tableRequests .= '<TBODY>';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<TR><TD COLSPAN=5>There are no requests. Try changing your filter.</TD></TR>';
        } else {
            foreach ($this->controllerModel->requestList as $request) {
              $tableRequests .= "\n<TR><TD>"  . $request->getUserNameReq(). "</TD>" .
                            '      <TD>' . $request->getUserEmailReq(). '</TD>' .
                            '      <TD>' . $request->getRequestItems()[0]->getBestTime() . '</TD>' .
                            '      <TD>' . $request->getRequestItems()[0]->getLangName() . '</TD>' .

                            '  </TR>';
            }
        }
        $tableRequests .= '</TBODY>';
        $tableRequests .= "\n</TABLE>";



        $output = str_replace('{s4uPlayerName}', $name , $output);

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
