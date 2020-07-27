<?php
// Boiler
// ViewPagesSobre.php: definiçao da view Sobre do controller cPages
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe a página Sobre
class ViewPagesAbout 
{
    public function output()
    {
        $output  = file_get_contents('classes/Views/htmlTemplates/headerPrincipal.html');
        $output .= '<div><h1>Sobre</h1></div>';
        $output .= '<div>Boiler is:<li>Fontes<li>Gambazza<li>Maya<li>Roxo</div>';
        $output .= '<div><BR><a href="boiler.php?c=CtlPages&action=home">Voltar para home</a></div>';

        $output = str_replace('{versao}', getenv('VER'), $output);
        return $output;

    }
}
