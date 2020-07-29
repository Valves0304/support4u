<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPhoneCallRequest
{
    private $controllerModel;   // model modified by controller

    // ViewPagesHome Construtor
    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/requestPhoneCall.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uInsertRequest}', Util::createLink("CtlRequests","insertRequest","2","1"), $output);

        $output = str_replace('{s4uListLangs}',
                              Util::createSelect(MdlLanguages::listLanguages(), 'getLangId', 'getLangName',
                                                 (!empty($this->controllerModel->errorField)) ? $this->controllerModel->request->getRequestItems()[0]->getLangId() : NULL),
                              $output);

        $output = str_replace('{s4uPhone}', !empty($this->controllerModel->errorField) ? $this->controllerModel->request->getRequestItems()[0]->getPhone() : '', $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        // set error messages
        if (!empty($this->controllerModel->errorField)) {
            $output = str_replace('{s4uErrorField' . $this->controllerModel->errorField . '}', 'has-error', $output);
        }
        $output = str_replace('{s4uErrorMessage}', $this->controllerModel->errorMsg, $output);

        return $output;
    }
}
