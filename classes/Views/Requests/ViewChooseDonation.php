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

        $output = str_replace('{s4uDonateGrocery}',"''",$output);
        $output = str_replace('{s4uDonateTalk}',"''",$output);
        $output = str_replace('{s4uDonateDog}',"''",$output);
        $output = str_replace('{s4uDonateGame}',"''",$output);
        $output = str_replace('{s4uDonateOther}',"''",$output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
