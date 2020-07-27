<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewGroceryDonation
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationGrocery.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newGroceryDonation"), $output);
        $output = str_replace('{s4uRequestMatch}', Util::createLink("CtlRequests","matchGrocery"), $output);

        // tag <SELECT> for Games

        $output = str_replace('{s4uListCities}',
                              Util::createSelect(MdlCities::listCities(), 'getCityId', 'getCityName',
                              isset($_POST['cityId']) ? $_POST['cityId'] : NULL),
                              $output);

        // table with requests
        $tableRequests = '';
        if ($this->controllerModel->requestList[0] == NULL) {
            $tableRequests .= '<h3>There are no requests. Try changing your filter.</h3>';
        } else {
            foreach ($this->controllerModel->requestList as $request) {
                $user = MdlUsers::findUser($request->getUserIdReq());

                    $tableRequests .=   '<label class="card-radio">'.
                                        '    <input type="radio" name="optionRequest" value="' . $request->getRequestId() . '">'.
                                        '    <article class="card">'.
                                        '        <div class="card-header"><i></i><h3>' . $user->getFirstName() . '</h3></div>'.
                                        '        <div class="card-content">'.
                                        '            <div><span>City</span>' . MdlCities::findCity($user->getCityId())->getCityName() . '</div>'.
                                        '            <div><span>Estimated Price</span>$' . number_format($request->getPrice(),2) . '</div>'.
                                        '            <div class="open-modal"><a href="#defaultModal" rel="modal:open"><i class="fas fa-th-list"></i> View List (' . count($request->getRequestItems()) . ')</a></div>'.
                                        '        </div>'.
                                        '        <div class="card-list">';
                                        foreach ($request->getRequestItems() as $requestItem) {
                                            $tableRequests .='<div>' . $requestItem->getItem() . ' <span>' . $requestItem->getQuantity() . ' <small>' . MdlUnits::findUnit($requestItem->getUnitId())->getUnitName() . '</small></span></div>';
                                        }
                    $tableRequests .= '        </div>'.
                                      '    </article>'.
                                        '</label>';

            }
        }

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
