<?php
// Support for you
// ViewPagesHome.php: Home Page View Home definition from controller ctlPages
// ---------------------------------------------------------------------------
// view Home Page
class ViewNewRequest
{
    // ViewPagesHome Construtor
    public function __construct()
    {
//        echo "<BR>Construtor da ViewNewRequest";
    }

    public function output()
    {
        $output  = ViewPagesHTMLHeader::output();
        $output .= file_get_contents('classes/Views/Requests/newRequest.html');
        $output .= file_get_contents('classes/Views/Pages/footer.html');

        // variables replacement
        $output = str_replace('{version}', getenv('VER'), $output);

        // agora você está chegando num problema que eu tive e cuja solução foi aquela view estática ViewUsuariosCabecalhoHTML (que hoje acho que deveria ser ViewPagesCabecalhoHTML)

        // a questão é: você vai repetir esse código em todas as views do sistema? Imagina o inferno de manutenção quando quiser mudar a mensagem, por exemplo.

        // por outro lado, encher o HTML de código é terrível, e quebra a SoC (Separation of Concerns).

        // A solução que encontrei foi criar uma view estática, a ser chamada por todas as views, que construa o cabeçalho. Ela vai concentrar toda a inteligêcia do cabeçalho,
        // como essas mudanças de itens de menu e links, no caso de usuário logado, ou a depender do nível.



        return $output;
    }
}
