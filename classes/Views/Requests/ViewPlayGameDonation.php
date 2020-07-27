<?php
// Support for you
// ---------------------------------------------------------------------------
class ViewPlayGameDonation
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationPlayGame.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newPlayGameDonation"), $output);
        $output = str_replace('{s4uRequestMatch}', Util::createLink("CtlRequests","matchPlayGame"), $output);

        // tag <SELECT> for Games
        $output = str_replace('{s4uListGames}',
                              Util::createSelect(MdlGames::listGames(), 'getGameId', 'getGameName',
                                                 isset($_POST['gameId']) ? $_POST['gameId'] : NULL),
                              $output);

        // table with requests
        $tableRequests = '<TABLE class="pure-table pure-table-bordered"><THEAD><TD>Select</TD><TD>Name</TD><TD>Platform</TD><TD>Game</TD><TD>Time</TD></THEAD> ';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<TR><TD COLSPAN=5>There are no requests. Try changing your filter.</TD></TR>';
        } else {
            foreach ($this->controllerModel->requestList as $request) {
                $tableRequests .= "\n<TR><TD>"  . '<input type="radio" name="optionRequest" value=" ' . $request->getRequestId() . ' "> ' .  "</TD>" .
                              '      <TD>' . MdlUsers::findUser($request->getUserIdReq())->getFirstName() . '</TD>' .
                              '      <TD>' . MdlGames::findGame($request->getRequestItems()[0]->getGameId())->getGameName() . '</TD>' .
                              '      <TD>' . $request->getRequestItems()[0]->getGameName() . '</TD>' .
                              '      <TD>' . $request->getRequestItems()[0]->getBestTime() . '</TD>' .


                              '  </TR>';
            }
        }
        $tableRequests .= "\n</TABLE>";

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
