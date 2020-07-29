<?php
// S4U
// MdlPages.php:
// autor: Vinicius Alves
// ---------------------------------------------------------------------------
require_once ($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

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
