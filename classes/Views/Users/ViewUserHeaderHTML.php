<?php
// SupportForYou
// ViewUsuariosCabecalhoHTML.php: output and substitution operations for header with user information
// autor: Team Support for you
// ---------------------------------------------------------------------------
class ViewUserHeaderHTML
{
    public static function output()
    {
        $output  = file_get_contents('../classes/Views/Pages/header.html');
        echo "Estou no ViewUserHeaderHTML";

        // substituição de variáveis
        if (isset($_SESSION['USUARIO'])) {
            $output = str_replace('{msg-usuario}','Hello, <a href=s4u.php?c=CtlUsers&action=register=' .
                                   $_SESSION['USUARIO'] . '>' .
                                   $_SESSION['NOME_USUARIO'] .
                                   '</a>. <a href=s4u.php?c=CtlUsers&action=logout><span class="fa fa-sign-out red-boiler-background"></span>Sair</a>', $output);

        } else {
            $output = str_replace('{msg-usuario}','<a href=s4u.php?c=CtlPagess&action=login><span class="fa fa-sign-in red-boiler-background"></span> Login</a>', $output);


        }
        return $output;

    }
}
