<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewNewGroceryRequest
{
    private $model;

    // Construtor
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/reqGrocery.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uCreateRequest}', Util::createLink("CtlRequests","insertRequest","1"), $output);

        for ($i = 0; $i < 10; $i++) {
            $output = str_replace('{s4uitem'. $i . '}',"",$output);
            $output = str_replace('{s4uqty'. $i . '}',"",$output);
            $output = str_replace('{s4uunit'. $i . '}',"",$output);
        }



        // tag <SELECT> for Units
        $output = str_replace('{s4uListUnits}',
                              Util::createSelect(MdlUnits::listUnits(), 'getUnitId', 'getUnitName', NULL),
                              $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
