<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUsers.php';


// encontrar user
$user = MdlUsers::findUser(13);
echo '<BR> User: ' . $user->getFirst_name();

//inclui cidade
//$senhorCliente = new User();
//$senhorCliente->setFirst_name("Mr M");
//$senhorCliente->setLast_name(" Fake");
//MdlUsers::insertUser($senhorCliente);

//listar todas as cidades
$list = MdlUsers::listUsers();

foreach($list as $user) {
  echo "<BR>".  $user->getUser_id(). " - "  . $user->getFirst_name() . " - " . $user->getLast_name();

}
