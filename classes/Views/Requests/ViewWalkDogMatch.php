<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewWalkDogMatch
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }


    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Donation/matchWalkDog.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uConfirmation}', Util::createLink("CtlRequests","matchDonation","","",$this->controllerModel->request->getRequestId()), $output);
        $output = str_replace('{s4uCancel}',  Util::createLink("CtlRequests","newWalkDogDonation"), $output);

        $user = MdlUsers::findUser($this->controllerModel->request->getUserIdReq());

        $output = str_replace('{s4uDogOwnerName}', $user->getFirstName(), $output);
        $output = str_replace('{s4uBeneficiaryName}', $user->getLastName() . ',' . $user->getFirstName(), $output);
        $output = str_replace('{s4uBeneficiaryEmail}', $user->getEmail(), $output);
        $output = str_replace('{s4uBeneficiaryBestTime}', $this->controllerModel->request->getRequestItems()[0]->getBestTime(), $output);
        $output = str_replace('{s4uBeneficiaryCity}', MdlCities::findCity($user->getCityId())->getCityName(), $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        return $output;
    }
}
