<?php
// SupportForYou
// ViewUsuariosCabecalhoHTML.php: output and substitution operations for header with user information
// autor: Team Support for you
// ---------------------------------------------------------------------------
class ViewUsuariosCabecalhoHTML
{
    public static function output()
    {
        $output  = file_get_contents('classes/Views/htmlTemplates/cabecalho.html');

        // substituição de variáveis
        if (isset($_SESSION['USUARIO'])) {
            $output = str_replace('{msg-usuario}','Olá, <a href=boiler.php?c=CtlAtletas&action=cadastraAtleta&usuario=' .
                                   $_SESSION['USUARIO'] . '>' .
                                   $_SESSION['NOME_USUARIO'] .
                                   '</a>. <a href=boiler.php?c=CtlUsuarios&action=logout><span class="fa fa-sign-out red-boiler-background"></span>Sair</a>', $output);
            if ($_SESSION['NIVAUT'] < Usuario::AUT_USUARIO_ADM_EVENTO) {
                $output = str_replace('{boilerMenuAdmin}','show', $output);
            } else {
                $output = str_replace('{boilerMenuAdmin}','hidden', $output);
            }
            if ($_SESSION['NIVAUT'] <= Usuario::AUT_USUARIO_ADM_EVENTO) {
                $output = str_replace('{boilerMenuWST}','show', $output);
            } else {
                $output = str_replace('{boilerMenuWST}','hidden', $output);
            }
        } else {
            $output = str_replace('{msg-usuario}','<a href=boiler.php?c=CtlPages&action=login><span class="fa fa-sign-in red-boiler-background"></span> Entrar</a>', $output);
            $output = str_replace('{boilerMenuAdmin}','hidden', $output);
            $output = str_replace('{boilerMenuWST}','hidden', $output);
        }
        return $output;

    }
}
