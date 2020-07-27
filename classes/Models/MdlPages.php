<?php
// Boiler
// MdlPages.php: definiçao do modelo da navegação básica do site
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// -> este é um modelo básico para o controle de páginas da aplicação
class MdlPages
{
    public $codError;
    public $msgError;

// MdlPages Constructor
    public function __construct()
    {
        $this->codError = null;
        $this->msgError = null;
    }



}
class HtmlHeaderItem
{
    private $fileType;
    private $fileURL;

    public function __construct($url = null) // user must provide the url of the file with the constructor - the class doens't have setters
    {
        $this->fileURL = $url;
        $this->fileType = (empty($url) ? "err" : substr($url, -3));
    }

    public function output()
    {
        $output = '';

        if (strtolower($this->fileType) == "css")
        {
            $output = '    <link rel="stylesheet" href="' . $this->fileURL . '">';
        }
        elseif (strtolower($this->fileType) == ".js")
        {
            $output = '    <script src="' . $this->fileURL . '"></script>';
        }
        else
        {
            $output = '<!-- Error: file extension unknown: ' . $url . '-->';
        }

        return $output;
    }
}
