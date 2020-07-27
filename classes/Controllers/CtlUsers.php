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
    // Atributos
    public $view;   // view escolhida pelo controller
    private $model; // modelo do controller, a ser modificado

    // Construtor
    public function __construct()
    {
        $this->view = null;
        $this->model = new MdlUsers();
    }

    // Métodos
    // Ações

    public function emailList()
    {
        $this->view = new ViewUsersEmailList();
    }

    public function login($userLogin = null)
    {
        // esse usuário do parâmetro serve para quando o sistema encontra (no CtlPages/login) um cookie com o nome do usuário e loga ele direto, sem precisar de senha
        // na maior parte das vezes, você vai receber usuário e senha via $_POST[]:
        // $_POST['login'] e $_POST['password']

        $this->view = new ViewPagesLogin($this->model);
        if (isset($_POST['login'])) {
            $userLogin = $_POST['login'];
        }
        $this->model->usuario = MdlUsers::findUserLogin($userLogin);

        if ($this->model->usuario === NULL                                                               // user not found
           or (isset($_POST['login']) AND !($this->model->usuario->checkUserPass($_POST['senha']))))     // OR wrong password (if via POST)
        {
            $mdl = new MdlPages();
            $mdl->msgError = 'User or password wrong';
            $mdl->campoErro = 1;
            $this->view = new ViewPagesLogin($mdl);
            return;
        }

        $_SESSION['ID_USUARIO'] = $this->model->usuario->getUserId();
        $_SESSION['USUARIO'] = $this->model->usuario->getUser_login();
        $_SESSION['NOME_USUARIO'] = $this->model->usuario->getFirst_name();

        echo "<BR>passei no usuario";
        echo "<BR> ". $_SESSION['NOME_USUARIO'];
        echo "<BR>passei no usuario " . $_SESSION['ID_USUARIO'] ;

        // define a view de novo request
        $mdl = new MdlRequests();
        $this->view = new ViewNewRequest($mdl);

    }

    //    $_SESSION['NIVAUT'] = $this->model->usuario->getNivelAutUsuario();

        // cria cookie se solicitado
  //      if (isset($_POST['lembrar']) && $_POST['lembrar'] == 'on') {
  //          setcookie ('boilerchannel', $userLogin, time() + (3600 * 24 * 30));
  //      }

        // verifica se a senha está expirada e chama a tela de troca de senha
/*      if ($this->model->usuario->getDataExpiracaoSenha() < new DateTime()) {
            $this->model->msgErro = 'Sua senha expirou. Por favor, informe uma nova senha';
            $this->model->campoErro = 1;
            $this->view = new ViewUsuariosTrocaSenha($this->model);
            return;
        }
*/
        // verifica se há uma URL de callback para desviar
/*        if (isset($_SESSION['URL_CALLBACK'])) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SESSION['URL_CALLBACK'];
            unset($_SESSION['URL_CALLBACK']);
            header('Location: ' . $url);
            exit;
        }

        // $mdl = new MdlAtletas();
        // $mdl->usuario = $this->model->usuario;
        // $mdl->atleta = MdlAtletas::encontraAtletaCPF($this->model->usuario->getCPFUsuario());
        // em último caso, chama tela de cadastro de usuario
        // $this->view = new ViewAtletasAtualizaCadastro($mdl);


    }


        // verifica se a senha está expirada e chama a tela de troca de senha        if ($this->model->usuario->getDataExpiracaoSenha() < new DateTime()) {
            $this->model->msgErro = 'Sua senha expirou. Por favor, informe uma nova senha';
            $this->model->campoErro = 1;
            $this->view = new ViewUsuariosTrocaSenha($this->model);
            return;
      }


        $mdl = new MdlAtletas();
        $mdl->usuario = $this->model->usuario;
        $mdl->atleta = MdlAtletas::encontraAtletaCPF($this->model->usuario->getCPFUsuario());

        // prepara o acesso a foto do atleta, se houver
        $mdl->atleta->setNomeArquivoFotoAtleta();

        // em último caso, chama tela de cadastro de usuario
        $this->view = new ViewAtletasAtualizaCadastro($mdl);

    }
*/

    public function logout()
    {
        unset($_SESSION['USUARIO']);
        unset($_SESSION['ID_USUARIO']);
        unset($_SESSION['NOME_USUARIO']);
        unset($_SESSION['NIVAUT']);
        setcookie ('boilerchannel', '', time() - 1); // expira o cookie
        $this->view = new ViewPagesLogin($this->model);

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

        $this->model->usuario = MdlUsuarios::encontraUsuarioLogin($_POST['login']);
        if ($this->model->usuario === NULL) {
            $this->model->usuario = MdlUsuarios::encontraUsuarioEmail($_POST['login']);
        }
        if ($this->model->usuario === NULL) {
            $this->model->usuario = MdlUsuarios::encontraUsuarioCPF($_POST['login']);
        }

        if ($this->model->usuario === NULL) {
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
        MdlUsuarios::atualizaUsuario($this->model->usuario);

        // envia email
        Util::enviaEmailTemplate('suporte@boilerchannel.com',
                                 $this->model->usuario->getEmailUsuario(),
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

        $this->model->usuario = MdlUsuarios::encontraUsuarioLogin($_SESSION['USUARIO']);
        if ($this->model->usuario === NULL) {
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

    public function cadastraUsuario()
    {
        $this->view = new ViewCadastroUsuario();
    }

    public function register()
    {

        // recebe parâmetros
        $userLogin  = isset($_GET['usuario']) ? $_GET['usuario'] : FALSE;
        $idAtleta      = isset($_GET['idAtleta']) ? $_GET['idAtleta'] : FALSE;
        $indPreCadastro= isset($_GET['pre']) ? TRUE : FALSE;

        // condições de autorização
        if (($userLogin AND $userLogin != $_SESSION['USUARIO']) OR              // apenas o próprio usuário pode realizar esta operação
            ($idAtleta AND !($indPreCadastro) AND                                     // pode ser um pré-cadastro, ou então
             $_SESSION['NIVAUT'] > Usuario::AUT_USUARIO_ADM_EVENTO)) {                // apenas adms podem alterar por idAtleta
            $this->view = new ViewPagesSemPermissao();
            return;
        }

        // prepara o modelo
        $this->model->atleta = new Atleta();
        $this->model->usuario = new Usuario();

        // carrega informações atuais na tela
        if ($userLogin) {
            $this->model->atleta = MdlAtletas::encontraAtletaLogin($userLogin);
            $this->model->usuario = MdlUsuarios::encontraUsuarioLogin($userLogin);
        }

        if ($idAtleta) {
            $this->model->atleta = MdlAtletas::encontraAtleta($idAtleta);
            if (!is_null($this->model->atleta) and !is_null($this->model->atleta->getIdUsuario())) {
                if ($indPreCadastro) {
                    // erro: usuário já cadastrado - envia para a tela de login
                    $this->view = new ViewPagesLogin(new MdlPages());
                    return;
                } else {
                    $this->model->usuario = MdlUsuarios::encontraUsuario($this->model->atleta->getIdUsuario());
                }
            }
        }

        // caso não seja um novo atleta, e o atleta não for encontrado...
        if (($userLogin OR $idAtleta) AND ($this->model->atleta === NULL)) {
            throw new Exception ('Atleta não encontrado');
        } else {
            // prepara o acesso a foto do atleta, se houver
            $this->model->atleta->setNomeArquivoFotoAtleta();
        }

        // exibe informações para atualização/inclusão
        $this->view = new ViewAtletasAtualizaCadastro($this->model);
    }


    // *******************************************************************************************************
    // *** listaUsuarios
    // *******************************************************************************************************
    // ***
    // *** exibe todos os usuarios que atendem ao critério passado
    // ***
    public function listaUsuarios()
    {
        // recebe parâmetros idEvento, tipo da lista e limite (paginação)
        $limite = isset($_GET['l']) ? $_GET['l'] : null;
        $tipo_lista = isset($_GET['lista']) ? $_GET['lista'] : 1;
        $evento = isset($_GET['idEvento']) ? $_GET['idEvento'] : null;
        if (!($evento) AND $tipo_lista == 1) {
            throw new Exception('Evento não informado');
        }

        // determina criterio
        if ($tipo_lista == 1) {
            $criterio = 'id_evento = ' . $this->model->dadosTransacao->dadosInscricaoEvento->idEvento;
        } else {
            $criterio = null;
        }

        $this->model->listaUsuarios = $this->model->listaUsuarios($criterio, $limite);
        $this->view = new ViewUsuariosLista($this->model);

    }

    public static function validaSenha($senha) {
        $r1='/[A-Z]/';  //Uppercase
        $r2='/[a-z]/';  //lowercase
        $r4='/[0-9]/';  //numbers

   //     if(preg_match_all($r1,$senha)<1) return FALSE;

   //     if(preg_match_all($r2,$senha)<1 or preg_match_all($r1,$senha)<1) return FALSE;

        if(preg_match_all($r4,$senha)<1) return FALSE;

        if(strlen($senha)<7) return FALSE;

        return TRUE;
    }
}
