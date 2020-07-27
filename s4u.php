<?php
// Support for you
// s4u.php - central controller MVC (Model–view–controller design pattern)
// ---------------------------------------------------------------------------
// - parameters:
// - GET['c'] = controller name (default: ctlPages)
// - GET['action'] = controller's action (default: error)
// - other parameters in GET or POST can be requested by the controllers
//require 'vendor/autoload.php'; // dependências dos pacotes instalados via composer - Cielo, Sendgrid, Kint, etc...

include 'loader.php';          // include all classes

//Array of all Controlers
$controllersTable = array('CtlPages', 'CtlUsers','CtlRequests');
$controller = null;

$controllerName = isset($_GET['c']) ? $_GET['c'] : "CtlPages";
$actionName = isset($_GET['action']) ? $_GET['action'] : null;

// verifica se usuário está logado. Se não estiver, seta o nível de autorização para "público"
//if (!isset($_SESSION['USER'])) {
//    $_SESSION['NIVAUT'] = Usuario::AUT_USUARIO_PUBLICO;
//}


// bloco try..fetch It tries to include definition of the chosen controller, instantiate, and execute the parameterized action
// if it fails, calls the error page (action 'error' of the controller 'CtlPages')
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
    $controller->error(0, 'Controller or Action unknwon');
}
// displays the view output chosen by the controller
echo $controller->view->output();
