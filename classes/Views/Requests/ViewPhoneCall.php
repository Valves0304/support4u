<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPhoneCall
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/reqPhoneCall.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uInsertRequest}', Util::createLink("CtlRequests","insertRequest","2","1"), $output);

        $output = str_replace('{s4uListLangs}',
                              Util::createSelect(MdlLanguages::listLangs(), 'getLangId', 'getLangName', NULL),
                              $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
