<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewWalkDogDonation
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationWalkDog.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newWalkDogDonation"), $output);
        $output = str_replace('{s4uRequestMatch}', Util::createLink("CtlRequests","matchWalkDog"), $output);

        // tag <SELECT> for Games

        $output = str_replace('{s4uListCities}',
                              Util::createSelect(MdlCities::listCities(), 'getCityId', 'getCityName',
                              isset($_POST['cityId']) ? $_POST['cityId'] : NULL),
                              $output);

        // table with requests
        $tableRequests = '<THEAD><TR><TD>Select</TD><TD>Name</TD><TD>City</TD><TD>Time</TD></TR></THEAD> ';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<TR><TD COLSPAN=5>There are no requests. Try changing your filter.</TD></TR>';
        } else {
            foreach ($this->controllerModel->requestList as $request) {
                $tableRequests .= "\n<TR><TD>"  . '<input type="radio" name="optionRequest" value=" ' . $request->getRequestId() . ' "> ' .  "</TD>" .
                              '      <TD>' . $request->getUserNameReq() . '</TD>' .
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
