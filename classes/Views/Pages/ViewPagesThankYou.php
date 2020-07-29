<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewPagesThankYou
{
    private $controllerModel;   // model modified by controller

    // ViewPagesHome Construtor
    public function __construct($model)
    {
        $this->controllerModel=$model;
    }

    public function output()
    {
        // retrieve request
        $request = $this->controllerModel->request;

        // output template
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Pages/thankYou.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement output
        $output = str_replace('{s4uChooseMain}', Util::createLink("CtlPages", "home"), $output);
        $output = str_replace('{s4uChooseDonation}', Util::createLink("CtlRequests", "chooseDonation"), $output);
        $output = str_replace('{s4uReqName}', MdlUsers::findUser($request->getUserIdReq())->getFirstName(), $output);
        $output = str_replace('{version}', getenv('VER'), $output);

        // email template
        $mail    = file_get_contents('classes/Views/Pages/emailMatch.html');

        // variable replacement email
        $mail = str_replace('{s4uDate}', date('d/m/y'), $mail);
        $mail = str_replace('{s4uRequestName}', MdlUsers::findUser($request->getUserIdReq())->getFirstName(), $mail);
        $mail = str_replace('{s4uDonorName}', MdlUsers::findUser($request->getUserIdDonor())->getFirstName(), $mail);
        $mail = str_replace('{s4uDonorEmail}', MdlUsers::findUser($request->getUserIdDonor())->getEmail(), $mail);
        

        // send email
        Util::sendEmail('admin@support4u.website', MdlUsers::findUser($request->getUserIdReq())->getEmail(), 'We have found a donor for your request!', $mail, MdlUsers::findUser($request->getUserIdReq())->getEmail());

        return $output;
    }
}
