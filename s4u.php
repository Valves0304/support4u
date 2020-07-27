<?php
// Support for you
// s4u.php - central controller MVC (Model–view–controller design pattern)
// autor: Vinicius Alves
// ---------------------------------------------------------------------------
// - parameters:
// - GET['c'] = controller name (default: ctlPages)
// - GET['action'] = controller's action (default: error)
// - other parameters in GET or POST can be requested by the controllers
//require 'vendor/autoload.php'; // dependências dos pacotes instalados via composer - Cielo, Sendgrid, Kint, etc...

include 'loader.php';          // include all classes

// força redirecionamento para protocolo seguro
//if (! is_https()) {
//    header("location: https://{$_SERVER['HTTP_HOST']}");
//}

//Array of all Controlers
$controllersTable = array('CtlPages', 'CtlUsers','CtlRequests');
$controller = null;

$controllerName = isset($_GET['c']) ? $_GET['c'] : "CtlPages";
$actionName = isset($_GET['action']) ? $_GET['action'] : null;

//echo "<BR>controller: " . $controllerName;
//echo "<BR>action: " . $actionName;

// verifica se usuário está logado. Se não estiver, seta o nível de autorização para "público"
//if (!isset($_SESSION['USUARIO'])) {
//    $_SESSION['NIVAUT'] = Usuario::AUT_USUARIO_PUBLICO;
//}

// seção de _overrides_ do controller chamado, em casos específicos

// chama a página de manutenção caso a variável esteja definida
//if (getenv('MANUT') == 'ON') {
//    $controllerName = 'CtlPages';
//    $actionName = 'manutencao';
//} else {

    ///ddd (MdlPages::nivelMinAutorizacaoAcesso($controllerName, $actionName), $controllerName, $actionName, $_SESSION['NIVAUT']);
    // testa se usuário possui nível mínimo para acessar a página solicitada
//    if (MdlPages::nivelMinAutorizacaoAcesso($controllerName, $actionName) < $_SESSION['NIVAUT']) {

        // se o acesso solicitado não é público e o usuário não está logado,
        // mostra a página de login
//        if (!isset($_SESSION['USUARIO'])) {
//            // guarda a url desejada para redirecionar posteriormente
//            $_SESSION['URL_CALLBACK'] = $_SERVER['REQUEST_URI'];
//            $controllerName = 'CtlPages';
//           $actionName = 'login';

//        } else {

            // senão, usuário vê tela de permissão insuficiente
//            $controllerName = 'CtlPages';
//            $actionName = 'semPermissao';
//        }
//    }
//}

// fim da seção de overrides

// bloco try..fetch tenta incluir definição do controller escolhido, instanciar, e executar a ação parametrizada
// caso falhe, chama a página de erro (ação 'erro' do controller 'CtlPages')
if (in_array($controllerName, $controllersTable)) {
    try {
        $controller = new $controllerName;

        if (!empty($actionName)) {
            if (method_exists($controller,$actionName)) {
                $controller->{$actionName}(null);
            } else {
                throw new Exception('Controller or Action unknwon');
            }
        }
    } catch (Exception $e) {
        $controller = new CtlPages();
        $controller->error(1, $e->getMessage());
    }

}

if (empty($controller) or empty($controller->view)) {
    $controller = new CtlPages();
    $controller->error(0, 'Controlador ou Ação desconhecidos');
}
// displays the view output chosen by the controller
echo $controller->view->output();

// ************************************************************************************
// função para identificar se estamos sob protocolo seguro
//function is_https() {
//    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 1) {
//        return TRUE;
//    } elseif (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
//       return TRUE;
//    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
//        return TRUE;
//    } elseif (isset($_SERVER['HTTP_X_HTTPS']) and $_SERVER['HTTP_X_HTTPS'] == 'on') {
//        return TRUE;
//    } else {
//        return FALSE;
//    }
//}
