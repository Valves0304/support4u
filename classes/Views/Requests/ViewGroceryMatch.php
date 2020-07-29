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
        $output = str_replace('{s4uConfirmation}', Util::createLink("CtlRequests", "matchDonation", "", "", $this->controllerModel->request->getRequestId()), $output);
        $output = str_replace('{s4uCancel}', Util::createLink("CtlRequests", "newGroceryDonation"), $output);

        // table with Grocery Items

        $request = $this->controllerModel->request;

        $user = MdlUsers::findUser($request->getUserIdReq());

        $userAndGroceryItems = '    <article class="card match">'.
                                '        <div class="card-header"><i></i><h3>' . $user->getFirstName() . '</h3></div>'.
                                '        <div class="card-content">'.
                                '            <div><span>City</span>' . MdlCities::findCity($user->getCityId())->getCityName() . '</div>'.
                                '            <div><span>eMail</span>' . $user->getEmail() . '</div>'.
                                '            <div><span>Estimated Price</span>$' . number_format($request->getPrice(), 2) . '</div>'.
                                '            <div class="open-modal"><a href="#defaultModal" rel="modal:open"><i class="fas fa-th-list"></i> View List (' . count($request->getRequestItems()) . ')</a></div>'.
                                '        </div>'.
                                '        <div class="card-list">';
        foreach ($request->getRequestItems() as $requestItem) {
            $userAndGroceryItems .='<div>' . $requestItem->getItem() . ' <span>' . $requestItem->getQuantity() . ' <small>' . MdlUnits::findUnit($requestItem->getUnitId())->getUnitName() . '</small></span></div>';
        }
        $userAndGroceryItems .= '        </div>'.
                              '    </article>'.
                                '</label>';

        $output = str_replace('{s4uUserAndGroceryItems}', $userAndGroceryItems, $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
