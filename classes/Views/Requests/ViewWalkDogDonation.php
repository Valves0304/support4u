<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewWalkDog
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/reqWalkDog.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uInsertRequest}', Util::createLink("CtlRequests","insertRequest","2","3"), $output);

        $output = str_replace('{s4uDogMorning}',
        $output = str_replace('{s4uDogAfternoon}',
        $output = str_replace('{s4uDogNight}',
        $output = str_replace('{s4uDogAnytime}',



        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
