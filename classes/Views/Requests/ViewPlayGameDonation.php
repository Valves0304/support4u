<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPlayGameDonation
{
    // ViewPlayGame Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationPlayGame.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
           $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newPlayDonation"), $output);

        // tag <SELECT> for Games
        $output = str_replace('{s4uListGames}',
                              Util::createSelect(MdlGames::listGames(), 'getGameId', 'getGameName', NULL),
                              $output);
        // tag <SELECT> for Requests
        $output = str_replace('{s4uTableRequests}',
        Util::createSelect(MdlRequests::listRequests(), 'getRequestId', 'getUserIdReq', NULL), $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
