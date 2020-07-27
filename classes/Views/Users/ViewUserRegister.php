<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewUserRegister
{
    private $controllerModel;   // model modified by controller

    // ViewUserRegister Construtor
    public function __construct($model)
    {
      $this->controllerModel=$model;
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Users/userRegister.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output);

        // tag <SELECT> for Cities
        $output = str_replace('{s4uListCities}',
                              Util::createSelect(MdlCities::listCities(), 'getCityId', 'getCityName', $this->controllerModel->user->getCityId()),
                              $output);

        $output = str_replace('{s4uFName}', $this->controllerModel->user->getFirstName(), $output);
        $output = str_replace('{s4uLName}', $this->controllerModel->user->getLastName(), $output);
        $output = str_replace('{s4uEmail}', $this->controllerModel->user->getEmail(), $output);
        $output = str_replace('{s4uAddress}', $this->controllerModel->user->getAddress(), $output);
        $output = str_replace('{s4uLogin}', $this->controllerModel->user->getUserLogin(), $output);
        $output = str_replace('{s4uPass}', "", $output);

        if (isset($_SESSION['NOME_USUARIO'])) {
                  $output = str_replace('{s4uButton}', "Update", $output);
                  $output = str_replace('{s4uVisible}', "logHidden", $output);
        } else {
                  $output = str_replace('{s4uButton}', "Sign up", $output);
                  $output = str_replace('{s4uVisible}', "logVisible", $output);
        }

        $output = str_replace('{s4uRegisterLink}', Util::createLink("CtlUsers","userUpdate"), $output);

        // set error messages
        if (!empty($this->controllerModel->errorField)) {
            $output = str_replace('{s4uErrorField' . $this->controllerModel->errorField . '}', 'has-error', $output);
        }
        $output = str_replace('{s4uErrorMessage}', $this->controllerModel->errorMsg, $output);

        return $output;
    }
}
