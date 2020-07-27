<?php
// Boiler
// ViewPagesHome.php: definiçao da view de Home do controller cPages
// ---------------------------------------------------------------------------
// a view exibe a página Home
class ViewUsersEmailList 
{
    
    // Construtor
    public function __construct() 
    {
//        echo "<BR>Construtor da ViewPagesHome";
    }
    
    public function output() 
    {
        $output .= file_get_contents('classes/Views/Pages/header.html');        
        $output .= file_get_contents('classes/Views/Users/emailList.html');        
        $output .= file_get_contents('classes/Views/Pages/footer.html');


        $lista = MdlUsers::listUsers();
        $emailList = "";
        
        foreach($lista as $user) {
            $emailList .= file_get_contents('classes/Views/Users/emailListRow.html');        
            $emailList = str_replace('{userName}', $user->getLast_name() . ", " . $user->getFirst_name(), $emailList);
            $emailList = str_replace('{userEmail}', $user->getEmail(), $emailList);
        }
        
        // substituição de variáveis 
        $output = str_replace('{versao}', getenv('VER'), $output);
        $output = str_replace('{emailList}', $emailList, $output);

        return $output;
    }
}
