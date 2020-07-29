<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPlayGameRequest
{
    private $controllerModel;   // model modified by controller

    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/Request/requestPlayGame.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{s4uInsertRequest}', Util::createLink("CtlRequests","insertRequest","2","2"), $output);

        // tag <SELECT> for Games
        $output = str_replace('{s4uListGames}',
                              Util::createSelect(MdlGames::listGames(), 'getGameId', 'getGameName',
                              (!empty($this->controllerModel->errorField)) ? $this->controllerModel->request->getRequestItems()[0]->getGameId() : NULL),
                              $output);

        $output = str_replace('{s4uGameName}', !empty($this->controllerModel->errorField) ? $this->controllerModel->request->getRequestItems()[0]->getGameName() : '', $output);

        $output = str_replace('{version}', getenv('VER'), $output);

        // set error messages
        if (!empty($this->controllerModel->errorField)) {
            $output = str_replace('{s4uErrorField' . $this->controllerModel->errorField . '}', 'has-error', $output);
        }
        $output = str_replace('{s4uErrorMessage}', $this->controllerModel->errorMsg, $output);

        return $output;
    }
}
