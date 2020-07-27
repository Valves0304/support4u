<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPhoneCallDonation
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationPhoneCall.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newPhoneCallDonation"), $output);
        $output = str_replace('{s4uRequestMatch}',  Util::createLink("CtlRequests","matchPhoneCall"), $output);

        // tag <SELECT> for Languages
      $output = str_replace('{s4uListLanguages}',
                            Util::createSelect(MdlLanguages::listLanguages(), 'getLangId', 'getLangName',
                                               (!empty($this->controllerModel->errorField)) ? $this->controllerModel->request->getRequestItems()[0]->getLangId() : NULL),
                             $output);


        // table with requests
        $tableRequests = '<TABLE class="pure-table pure-table-bordered"><THEAD><TD>Select</TD><TD>Name</TD><TD>Time</TD><TD>Language</TD></THEAD> ';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<TR><TD COLSPAN=5>There are no requests. Try changing your filter.</TD></TR>';
        } else {
            foreach ($this->controllerModel->requestList as $request) {

                $tableRequests .= "\n<TR><TD>"  . '<input type="radio" name="optionRequest" value="' . $request->getRequestId(). '"> ' .  "</TD>" .
                              '      <TD>' . $request->getUserNameReq() . '</TD>' .
                              '      <TD>' . $request->getRequestItems()[0]->getBestTime() . '</TD>' .
                              '      <TD>' . $request->getRequestItems()[0]->getLangName() . '</TD>' .

                              '  </TR>';
            }
        }

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
