<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlRequests.php';
include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUsers.php';

$listDogRequests = array();
$listDogRequests = MdlRequests::listDogRequests(4);


foreach ($listDogRequests as $request) {

     echo '<BR> - ';
     $user    = MdlUsers::findUser($request->getUserIdReq());
    // $name    = $user->getFirstName();
    // echo $name;
     echo '<BR> - ' . $user->getFirstName() . ' ' . $user->getLastName() . ' ';

}
