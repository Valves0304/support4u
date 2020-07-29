<?php

// Tools
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Util.php';

// Controllers
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlUsers.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlRequests.php';

// Models
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCities.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlGames.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUnits.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlLanguages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlRequests.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUsers.php';

// Views

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesLogin.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesAbout.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesHome.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesThankYou.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesHTMLHeader.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesErro.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewChooseDonation.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewChooseRequest.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewGetStarted.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewGroceryRequest.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewGroceryDonation.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewGroceryMatch.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPhoneCallRequest.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPhoneCallDonation.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPhoneCallMatch.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPlayGameRequest.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPlayGameDonation.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewPlayGameMatch.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewSuccessfullyRequested.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewWalkDogRequest.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewWalkDogDonation.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Requests/ViewWalkDogMatch.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Users/ViewUserRegister.php';

// variáveis de ambiente
    include $_SERVER['DOCUMENT_ROOT'] . '/var.php';
