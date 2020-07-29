<?php
// S4U
// ViewPagesAbout.php: About view of CtlPages
// autor: Support4u team
// ---------------------------------------------------------------------------
class ViewPagesAbout
{
    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Pages/about.html');
        #$output .= '<div><BR><a href="' . Util::createLink("CtlPages","home") . '">Back HomePage</a></div>';
        $output .= file_get_contents('classes/Views/Pages/footer.html');
        $output = str_replace('{version}', getenv('VER'), $output);
        return $output;
    }
}
