<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewChooseDonation
{
    // ViewPagesHome Construtor
    public function __construct()
    {

    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/chooseDonationButtons.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement

        $output = str_replace('{s4uDonateGrocery}',   Util::createLink("CtlRequests","newGroceryDonation"),$output);
        $output = str_replace('{s4uDonatePhoneCall}', Util::createLink("CtlRequests","newPhoneCallDonation"), $output);
        $output = str_replace('{s4uDonateWalkDog}',   Util::createLink("CtlRequests","newDogDonation"), $output);
        $output = str_replace('{s4uDonatePlayGame}',  Util::createLink("CtlRequests","newPlayGameDonation"), $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
