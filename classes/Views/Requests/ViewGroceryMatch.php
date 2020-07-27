<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewGroceryMatch
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }


    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/matchGrocery.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');


        // variables replacement
        $output = str_replace('{s4uConfirmation}', Util::createLink("CtlRequests","matchDonation","","",$this->controllerModel->request->getRequestId()), $output);
        $output = str_replace('{s4uCancel}',  Util::createLink("CtlRequests","newGroceryDonation"), $output);

        // table with Grocery Items
        $groceryItems = '<TABLE class="pure-table pure-table-bordered"><THEAD><TR><TD>Item</TD><TD>Unit</TD><TD>Quantity</TD></TR></THEAD> ';
        $groceryItems .= '<TBODY>';
        foreach ($this->controllerModel->request->getRequestItems() as $requestItem) {
            $groceryItems .= "  <TR><TD>"  . $requestItem->getItem(). "</TD>" .
                             '      <TD>' . MdlUnits::findUnit($requestItem->getUnitId())->getUnitName() . '</TD>' .
                             '      <TD>' . $requestItem->getQuantity() . '</TD>' .
                             '  </TR>';
        }
        $groceryItems .= '</TBODY>';
        $groceryItems .= "\n</TABLE>";

        $output = str_replace('{s4uGroceryItems}', $groceryItems, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
