<?php
// SupportForYou
// ViewPagesHTMLHeader.php: output and substitution operations for header with user information
// author: S4U
// ---------------------------------------------------------------------------
class ViewPagesHTMLHeader
{
    public static function output($headerItems = null)
    {

        // initializes $output with header html
        $output  = file_get_contents('classes/Views/Pages/header.html');

        // checks if caller view wants to add .css or .js files
        $customHeaders = '';
        if (!is_null($headerItems)) {
            foreach ($headerItem as $headerItems) {
                $customHeaders .= $headerItem->output();    // output method must return a correct html instruction
            }
        }

        // tags replacement

        // custom headers, if any
        $output = str_replace('{s4uCustomHeaders}', $customHeaders, $output);

        // static links
        $output = str_replace('{s4uHomeLink}',Util::createLink("CtlPages","home"), $output);
        // $output = str_replace('{s4uAboutLink}',Util::createLink("CtlPages","about"), $output);
        $output = str_replace('{s4uUserRegisterLink}',Util::createLink("CtlUsers","userRegister"), $output);

        // dynamic links

        // checks if user is logged in
        if (isset($_SESSION['USER'])) {
            $output = str_replace('{s4uUserGreeting}','Hello, ' . $_SESSION['NAME_USER'] . '!', $output);

            $output = str_replace('{s4uSignUp}','My Register ', $output);
            $output = str_replace('{s4uGetStartedLink}',Util::createLink("CtlRequests","getStarted"), $output);
            $output = str_replace('{s4uGetStarted}','Get Started', $output);

            $output = str_replace('{s4uLoginLogoutLink}',Util::createLink("CtlUsers","logout"), $output);
            $output = str_replace('{s4uLoginLogout}','Logout', $output);

        } else {
            $output = str_replace('{s4uUserGreeting}','', $output);            
            $output = str_replace('{s4uLoginLogoutLink}',Util::createLink("CtlUsers","login"), $output);
            $output = str_replace('{s4uLoginLogout}','Login', $output);
            $output = str_replace('{s4uSignUp}','Sign up', $output);
            $output = str_replace('{s4uGetStartedLink}',Util::createLink("CtlPages","about"), $output);
            $output = str_replace('{s4uGetStarted}','About', $output);

        }
        return $output;

    }
}
