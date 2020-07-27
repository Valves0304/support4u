<?php

include $_SERVER['DOCUMENT_ROOT'] . '/loader.php';

// encontrar senha
//$senha = "";
//$usuario = new MdlUsers();
//$senha = $usuario->setUserPass("123456");
//echo "<BR>" . $senha;

echo "<BR><BR> atualizando a senha no banco:";
$usr = MdlUsers::findUserLogin("Lucas");
echo "<BR>Usuário encontrado:" . $usr->getFirstName() . " " . $usr->getLasttName();
//echo "<BR>setando senha 123456";
//$usr->setUserPass("123456");

echo "<BR>senha criptografada: " . $usr->getCryptUserPass();

//echo "<BR>Atualização do usuário: ";
//try {
//    MdlUsers::updateUser($usr);
//    echo " com sucesso";
//} catch (Exception $e) {
//    echo "deu erro";
//}

echo "<BR>senha certa: " . ($usr->checkUserPass("123456") ? "certa!" : "errada!");
