<?php

include $_SERVER['DOCUMENT_ROOT'] . '/loader.php';

// encontrar senha
//$senha = "";
//$usuario = new MdlUsers();
//$senha = $usuario->setUserPass("123456");
//echo "<BR>" . $senha;


echo "<BR><BR> atualizando a senha no banco:";
$usr = MdlUsers::findUserLogin("Lucas");

$usr->setUserPass("123456");
echo "<BR>Atualização do usuário: ";
try {
    MdlUsers::updateUser($usr);
    echo " com sucesso";
} catch (Exception $e) {
    echo "deu erro";
}



echo "<BR>Usuário encontrado:" . $usr->getFirstName() . " " . $usr->getLasttName();
echo "<BR><BR> testando senha:";
echo "<BR>senha errada: " . ($usr->checkUserPass("qualquer coisa") ? "certa!" : "errada!");
echo "<BR>senha certa: " . ($usr->checkUserPass("123456") ? "certa!" : "errada!");
