<?php

// Tools
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Util.php';

// Controllers
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlUsers.php';

// Models
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCities.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlGames.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlLanguages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlRequests.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUsers.php';

// Views

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesErro.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesManutencao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesLogin.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesSemPermissao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesHome.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewNewRequest.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUserRegister.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUsersEmailList.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUsuariosCabecalhoHTML.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUsuariosTrocaSenha.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUsuariosEsqueceuSenha.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUsuariosSucessoTrocaSenha.php';


// variáveis de ambiente
    include $_SERVER['DOCUMENT_ROOT'] . '/var.php';
