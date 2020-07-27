<?php
// Support for you
// ---------------------------------------------------------------------------

class ViewWalkDogDonation
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/donationWalkDog.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uFilterRequest}', Util::createLink("CtlRequests","newWalkDogDonation"), $output);
        $output = str_replace('{s4uRequestMatch}', Util::createLink("CtlRequests","matchWalkDog"), $output);

        // tag <SELECT> for Cities
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
                                    '<input type="radio" name="optionRequest" value="' . $request->getRequestId(). '"> '.
                                    '<article class="card">'.
                                        '<div class="card-header"><i></i><h3>' . MdlUsers::findUser($request->getUserIdReq())->getFirstName() . '</h3></div>'.
                                        '<div class="card-content">'.
                                            '<div><span>Time</span>' . $request->getRequestItems()[0]->getBestTime() . '</div>'.
                                            '<div><span>City</span>' . MdlCities::findCity($user->getCityId())->getCityName() . '</div>'.
                                        '</div>'.
                                    '</article>'.
                                '</label>';
            }
        }

        $output = str_replace('{s4uTableRequests}', $tableRequests, $output);


        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
