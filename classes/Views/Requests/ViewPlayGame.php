<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPlayGame
{
    // ViewPlayGame Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/reqPlayGame.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uInsertRequest}', Util::createLink("CtlRequests","insertRequest","2","2"), $output);

        // tag <SELECT> for Games
        $output = str_replace('{s4uListGames}',
                              Util::createSelect(MdlGames::listGames(), 'getGameId', 'getGameName', NULL),
                              $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
