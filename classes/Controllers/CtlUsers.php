<?php
// s4u
// CtlUsers.php: users controller
// autor: Support for you team
// ---------------------------------------------------------------------------
// - controller actions:
// - login
// - registerUsuario
class CtlUsers
{
    // Attributes
    public $view;   // Controller chosen view
    private $model; // Controller model

    // Construtor
    public function __construct()
    {
        $this->view = null;
        $this->model = new MdlUsers();
    }

    // Methods
    // Actions

    public function emailList()
    {
        $this->view = new ViewUsersEmailList();
    }

    public function login($userLogin = null)
    {
        // this parameter user is for when the system finds (in CtlPages / login) a cookie with the username and logs it directly, without needing a password

        $this->view = new ViewPagesLogin($this->model);
        if (isset($_POST['login'])) {
            $userLogin = $_POST['login'];
        }

        $this->model->usuario = MdlUsers::findUserLogin($userLogin);

        if ($this->model->usuario === NULL                                                               // user not found
           or (isset($_POST['login']) AND !($this->model->usuario->checkUserPass($_POST['password']))))     // OR wrong password (if via POST)
        {
            $mdl = new MdlPages();
            $mdl->msgError = 'User or password wrong';

            if (empty($_POST['login'])){
              $mdl->msgError = '';
            }

            $mdl->codError = 1;
            $this->view = new ViewPagesLogin($mdl);
            return;
        }

        $_SESSION['ID_USUARIO'] = $this->model->usuario->getUserId();
        $_SESSION['USUARIO'] = $this->model->usuario->getUserLogin();
        $_SESSION['NOME_USUARIO'] = $this->model->usuario->getFirstName();

        // define a view de novo request
        $this->view = new ViewGetStarted();

    }


        // verifica se a senha está expirada e chama a tela de troca de senha
/*      if ($this->model->user->getDataExpiracaoSenha() < new DateTime()) {
            $this->model->msgErro = 'Sua senha expirou. Por favor, informe uma nova senha';
            $this->model->campoErro = 1;
            $this->view = new ViewUsuariosTrocaSenha($this->model);
            return;
        }
*/

    public function logout()
    {
        unset($_SESSION['USUARIO']);
        unset($_SESSION['ID_USUARIO']);
        unset($_SESSION['NOME_USUARIO']);
//        unset($_SESSION['NIVAUT']);
//        setcookie ('boilerchannel', '', time() - 1); // expira o cookie
        $this->view = new ViewPagesHome();

    }

    public function esqueceuSenha()
    {
        if (!(isset($_POST['login'])) || $_POST['login'] == '') {
            $mdl = new MdlPages();
            $mdl->msgErro = 'Informe o CPF ou email';
            $mdl->campoErro = 1;
            $this->view = new ViewPagesLogin($mdl);
            return;
        }

        $this->model->user = Mdlusers::encontraUsuarioLogin($_POST['login']);
        if ($this->model->user === NULL) {
            $this->model->user = Mdlusers::encontraUsuarioEmail($_POST['login']);
        }
        if ($this->model->user === NULL) {
            $this->model->user = Mdlusers::encontraUsuarioCPF($_POST['login']);
        }

        if ($this->model->user === NULL) {
            $mdl = new MdlPages();
            $mdl->msgErro = 'Usuário não encontrado';
            $mdl->campoErro = 1;
            $this->view = new ViewPagesLogin($mdl);
            return;
        }

        // atribui nova senha
        $novaSenha = MdlUsuarios::geraSenha();
        $this->model->usuario->setSenhaUsuario($novaSenha);

        // expira senha
        $this->model->usuario->expiraSenha();

        // atualiza usuario
        Mdlusers::atualizaUsuario($this->model->user);

        // envia email
        Util::enviaEmailTemplate('suporte@boilerchannel.com',
                                 $this->model->user->getEmailUsuario(),
                                 '3c630bc5-e407-494e-ad34-2054c365863c',
                                 array('%senha%' => $novaSenha));

        // exibe mensagem avisando envio de email
        $this->view = new ViewUsuariosEsqueceuSenha();

    }

    // troca senha
    public function trocaSenha()
    {
        if (!(isset($_POST['senha'])) or !(isset($_POST['confirma']))) {
            throw new Exception('Senha ou confirmação não informados');
        }

        $this->model->user = Mdlusers::encontraUsuarioLogin($_SESSION['user']);
        if ($this->model->user === NULL) {
            $mdl = new MdlPages();
            $mdl->msgErro = 'Usuário ou senha incorretos';
            $mdl->campoErro = 1;
            $this->view = new ViewPagesLogin($mdl);
            return;
        }

        if ($_POST['senha'] <> $_POST['confirma']) {
            $mdl = new MdlUsuarios();
            $mdl->msgErro = 'Senhas não conferem';
            $mdl->campoErro = 2;
            $this->view = new ViewUsuariosTrocaSenha($mdl);
            return;
        }

        if (!$this->validaSenha($_POST['senha'])) {
            $mdl = new MdlUsuarios();
            $mdl->msgErro = 'Senha inválida';
            $mdl->campoErro = 1;
            $this->view = new ViewUsuariosTrocaSenha($mdl);
            return;
        }

        if ($this->model->usuario->checkUserPass($_POST['senha'])) {
            $mdl = new MdlUsuarios();
            $mdl->msgErro = 'Senha igual à anterior';
            $mdl->campoErro = 1;
            $this->view = new ViewUsuariosTrocaSenha($mdl);
            return;
        }

        // atualiza a senha no usuario do modelo
        $this->model->usuario->setSenhaUsuario($_POST['senha']);

        // persiste a nova senha no BD
        $this->model->atualizaUsuario($this->model->usuario);

        // chama a view de sucesso na troca de senha
        $this->view = new ViewUsuariosSucessoTrocaSenha($this->model);
    }

    public function userRegister()
    {
      //  $this->view = new ViewUserRegister();
      //  $_SESSION['USUARIO']);
      if (isset($_SESSION['USUARIO'])){
        $this->model->user = MdlUsers::findUserLogin($_SESSION['USUARIO']);
      }
      else
      {
        $this->model->user = new User();
      }
        // exibe informações para atualização/inclusão
      $this->view = new ViewUserRegister($this->model);
    }

    // *******************************************************************************************************
    // *** userUpdate
    // *******************************************************************************************************
    // insert or update user based on user registration form
    //
    public function userUpdate()
    {
        // checks whether it's an update or insert
        if (isset($_SESSION['USUARIO'])) {
            $this->model->user = MdlUsers::findUserLogin($_SESSION['USUARIO']);
        } else {
            $this->model->user = new User();
            $this->model->user->setUserLogin($_POST['login']);
            $this->model->user->setUserPass($_POST['pass']);
        }

        // sets the User object with form input
        //The FILTER_SANITIZE_EMAIL filter removes all illegal characters from an email address
        $this->model->user->setFirstName($_POST['firstname']);
        $this->model->user->setLastName($_POST['lastname']);
        $this->model->user->setEmail(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $this->model->user->setCityId($_POST['cityId']);
        $this->model->user->setAddress($_POST['address']);

        // validates form input
        if ($this->userFormValidation($this->model->user)) {

            // error found. Return to user form and display error message
            $this->view = new ViewUserRegister($this->model);
            return;
        }

        // insert or update user
        if ($this->model->user->getUserId() === NULL) {

            MdlUsers::insertUser($this->model->user);

            // log user in
            $_SESSION['ID_USUARIO'] = $this->model->user->getUserId();
            $_SESSION['USUARIO'] = $this->model->user->getUserLogin();
            $_SESSION['NOME_USUARIO'] = $this->model->user->getFirstName();

            // define the view
            $this->view = new ViewGetStarted();

        } else {

            MdlUsers::updateUser($this->model->user);

            // user could have changed names...
            $_SESSION['NOME_USUARIO'] = $this->model->user->getFirstName();

            // define the view
            $this->view = new ViewGetStarted();
        }
    }

    // *******************************************************************************************************
    // *** userFormValidation
    // *******************************************************************************************************
    // - PRIVATE method, should be called only within Class' scope
    // - MUST receive valid User object
    // - Sets Model's error field and message
    // - Returns on the first error found
    // - Returns 1 on error, 0 on success
    private function userFormValidation($user)
    {
        $this->model->errorField = null;
        $this->model->errorMsg = null;

        // On new user, login must be unique and password must be a valid one
        if ($user->getUserId() === NULL) {
            if ($user->getUserLogin() == '') {
                $this->model->errorField = 6;
                $this->model->errorMsg = "Must inform user login";
                return 1;
            }

            if (MdlUsers::findUserLogin($user->getUserLogin())) {
                $this->model->errorField = 6;
                $this->model->errorMsg = "Login already taken";
                return 1;
            }

            if (!(CtlUsers::passwordValidation($_POST['pass']))) {
                $this->model->errorField = 7;
                $this->model->errorMsg = "Password not valid - at least six characters and 1 number";
                return 1;
            }

        }

        // user first and last name
        if ($user->getFirstName() == '') {
            $this->model->errorField = 1;
            $this->model->errorMsg = "Please inform first name";
            return 1;
        }

        if ($user->getLastName() == '') {
            $this->model->errorField = 2;
            $this->model->errorMsg = "Please inform last name";
            return 1;
        }

        // email must be informed, valid and unique
        if ($user->getEmail() == '') {
            $this->model->errorField = 3;
            $this->model->errorMsg = "Please inform email";
            return 1;
        }

        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $this->model->errorField = 3;
            $this->model->errorMsg = "Please inform valid email";
            return 1;
        }

        $userEmail = MdlUsers::findUserEmail($user->getEmail());
        if (!is_null($userEmail) and $userEmail->getUserId() <> $user->getUserId() ) {
            $this->model->errorField = 3;
            $this->model->errorMsg = "Email already taken";
            return 1;
        }

        // Missing City and Address validation!


        // No errors
        return 0;
    }


    // *******************************************************************************************************
    // *** passwordValidation
    // *******************************************************************************************************
    public static function passwordValidation($pass) {
        $r1='/[A-Z]/';  //Uppercase
        $r2='/[a-z]/';  //lowercase
        $r4='/[0-9]/';  //numbers

   //     if(preg_match_all($r1,$pass)<1) return FALSE;

   //     if(preg_match_all($r2,$pass)<1 or preg_match_all($r1,$pass)<1) return FALSE;

        if(preg_match_all($r4,$pass)<1) return FALSE;

        if(strlen($pass)<6) return FALSE;

        return TRUE;
    }
}
