<?php
// Util.php: useful functions
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

class Util 
{
    // *******************************************************************************************************
    // *** sendEmail
    // *******************************************************************************************************
    // ***
    // *** sends a plain text or html email
    // ***
    public static function sendEmail($from, $to, $subject, $message, $replyTo) 
    {

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
        // Create email headers
        $headers .= 'From: ' . $from ."\r\n" .
                    'Reply-To: ' . $replyTo ."\r\n" .
                    'X-Mailer: PHP/' . phpversion();
 
        // Sending email
        if (!mail($to, $subject, $message, $headers)) {
            $msgException  = 'There was an error sending email: <BR>';
            $msgException .= $e->getCode() . '<BR>';
            foreach($e->getErrors() as $er) {
               $msgException .= $er . '<BR>';
            }

            // log the error
            error_log(date(DATE_ATOM) . ' - Error sending  email' . PHP_EOL, 3, getenv('LOG_FILE'));
            error_log('                          - Exceptio dump:' . PHP_EOL, 3, getenv('LOG_FILE'));
            error_log($msgException, 3, getenv('LOG_FILE'));

            throw new exception ($msgException);
            
        }
    }
 

    // *******************************************************************************************************
    // *** hash_equals
    // *******************************************************************************************************
    // ***
    // *** compara dois hashs de forma segura (ausente em versoes antigas de PHP)
    // ***
    public static function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
    
    // *******************************************************************************************************
    // *** createSelect
    // *******************************************************************************************************
    // ***
    // *** cria uma tag HTML select a partir de um array de objetos 
    // *** a função espera receber o nome dos métodos a chamar para obter os valores do select
    // *** é possível informar um valor de chave para vir selecionado
    // *** obs: apenas os itens <OPTION> são gerados, por conta das várias opções de SELECT
    public static function createSelect($lista, $funcaoChave, $funcaoTexto, $chaveSelecionada = NULL, $funcaoTextoData = NULL) 
    {
        $output = '';
        
        foreach($lista as $objeto) {

            $output .= '<OPTION VALUE=' 
                    .  $objeto->{$funcaoChave}() 
                    .  (!is_null($funcaoTextoData) ? ' data-aux="' . $objeto->{$funcaoTextoData}() . '" ' : '')
                    .  ((!is_null($chaveSelecionada) and $objeto->{$funcaoChave}() == $chaveSelecionada) ? ' SELECTED>' : '>')
                    .  $objeto->{$funcaoTexto}() 
                    . '</OPTION> <BR>';
        }    

        return $output;
    }
    
    
    // *******************************************************************************************************
    // *** resizeImage
    // *******************************************************************************************************
    // ***
    // *** Resize an image and keep the proportions
    // *** @author Allison Beckwith <allison@planetargon.com>  
    public static function resizeImage($filename, $type, $max_width, $max_height)
    {
        list($orig_width, $orig_height) = getimagesize($filename);

        $width = $orig_width;
        $height = $orig_height;

        # taller
        if ($height > $max_height) {
            $width = ($max_height / $height) * $width;
            $height = $max_height;
        }

        # wider
        if ($width > $max_width) {
            $height = ($max_width / $width) * $height;
            $width = $max_width;
        }

        $imagemNova = imagecreatetruecolor($width, $height);
        
        // tenta criar imagem a partir do arquivo informado. 
        switch($type){
            case 'image/bmp': $imagemAntiga = Util::imagecreatefrombmp($filename); break;
            case 'image/gif': $imagemAntiga = imagecreatefromgif($filename); break;
            case 'image/jpeg': $imagemAntiga = imagecreatefromjpeg($filename); break;
            case 'image/png': $imagemAntiga = imagecreatefrompng($filename); break;
            default : return null;
        }        

        imagecopyresampled($imagemNova, $imagemAntiga, 0, 0, 0, 0, 
                                     $width, $height, $orig_width, $orig_height);

        return $imagemNova;
    }
    
    // não há função nativa em PHP para criar um bmp
    public static function imagecreatefrombmp( $filename ) 
    { 
        $file = fopen( $filename, "rb" ); 
        $read = fread( $file, 10 ); 
        while( !feof( $file ) && $read != "" ) 
        { 
            $read .= fread( $file, 1024 ); 
        } 
        $temp = unpack( "H*", $read ); 
        $hex = $temp[1]; 
        $header = substr( $hex, 0, 104 ); 
        $body = str_split( substr( $hex, 108 ), 6 ); 
        if( substr( $header, 0, 4 ) == "424d" ) 
        { 
            $header = substr( $header, 4 ); 
            // Remove some stuff? 
            $header = substr( $header, 32 ); 
            // Get the width 
            $width = hexdec( substr( $header, 0, 2 ) ); 
            // Remove some stuff? 
            $header = substr( $header, 8 ); 
            // Get the height 
            $height = hexdec( substr( $header, 0, 2 ) ); 
            unset( $header ); 
        } 
        $x = 0; 
        $y = 1; 
        $image = imagecreatetruecolor( $width, $height ); 
        foreach( $body as $rgb ) 
        { 
            $r = hexdec( substr( $rgb, 4, 2 ) ); 
            $g = hexdec( substr( $rgb, 2, 2 ) ); 
            $b = hexdec( substr( $rgb, 0, 2 ) ); 
            $color = imagecolorallocate( $image, $r, $g, $b ); 
            imagesetpixel( $image, $x, $height-$y, $color ); 
            $x++; 
            if( $x >= $width ) 
            { 
                $x = 0; 
                $y++; 
            } 
        } 
        return $image; 
    }  
    
    // *******************************************************************************************************
    // *** validaCPF
    // *******************************************************************************************************
    // ***
    public static function validaCPF($cpf = null) {
     
        // Verifica se um numero foi informado
        if(empty($cpf)) {
            return false;
        }
     
        // Elimina possivel mascara
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        // Verifica se o numero de digitos informados e igual a 11 
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequencias invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {   
             
            for ($t = 9; $t < 11; $t++) {
                 
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
     
            return true;
        }
    }    
    
    //Luhn algorithm identifier verification
    public static function checksum($card_number)
    {
        $card_number_checksum = '';

        foreach (str_split(strrev((string) $card_number)) as $i => $d) {
            $card_number_checksum .= $i %2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($card_number_checksum)) % 10 === 0;
    }
    
    // realiza teste de expressao regular para determinar o tipo de cartao
    public static function cardType($number)
    {
        $brands = array('visa'       => '/^4\d{12}(\d{3})?$/',
                    	'mastercard' => '/^(5[1-5]\d{4}|677189)\d{10}$/',
                    	'diners'     => '/^3(0[0-5]|[68]\d)\d{11}$/',
                    	'discover'   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
                    	'elo'        => '/^((((636368)|(438935)|(509067)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
                    	'amex'       => '/^3[47]\d{13}$/',
                    	'jcb'        => '/^(?:2131|1800|35\d{3})\d{11}$/',
                    	'aura'       => '/^(5078\d{2})(\d{2})(\d{11})$/',
        //            	'hipercard'  => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
        //            	'maestro'    => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
                    );
        $number=preg_replace('/[^\d]/','',(string)$number);
    	$brand = 'undefined';
	    foreach ($brands as $_brand => $regex ) {
		    if (preg_match($regex, $number ) ) {
			    $brand = $_brand;
			    break;
		    }
	    }
	    return $brand;
    }
    
    //  Executa query no banco de dados
    public static function query($query) {
        $db = Db::getInstance();
        $dadosRetorno = array();
      
        try {
            $result = $db->query($query);
        } catch (Exception $e) {
            return null;
        }
        
        if (is_null($result)) {
            return null;
        }
        // retorna um array com os valores
        if (is_object($result)) {
            while($linha = $result->fetch_assoc()) {
    
                array_push($dadosRetorno, $linha);  
            }
            return $dadosRetorno;
        } else {
            return $result;
        }
    }
    
    // *******************************************************************************************************
    // *** acertaCase
    // *******************************************************************************************************
    // ***
    // *** converte string para camelCase
    public static function acertaCase($texto) 
    {
        return ucwords(strtolower($texto));
    }
    
    // *******************************************************************************************************
    // *** removeAcentos
    // *******************************************************************************************************
    // ***
    // *** remove acentos de texto
    public static function removeAcentos($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;
    
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
    
        $string = strtr($string, $chars);
    
        return $string;
    }    
    
    // *******************************************************************************************************
    // *** postCURL
    // *******************************************************************************************************
    // ***
    // *** envia post para $url via cURL
    function postCURL ($url, $post_data) {

        //traverse array and prepare data for posting (key1=value1)
        foreach ( $post_data as $key => $value) {
            $post_items[] = $key . '=' . urlencode($value);
        }
        
        //create the final string to be posted using implode()
        $post_string = implode ('&', $post_items);
        
        //create cURL connection
        $curl_connection = curl_init($url);
        
        //set options
        curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
        
        //set data to be posted
        curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
        
        //perform our request
        $result = curl_exec($curl_connection);
        
        //show information regarding the request
        // d (curl_getinfo($curl_connection), $post_string);
        
        //close the connection
        curl_close($curl_connection);
        
        return $result;

    }
    
    function urlString($post_data) {
        //traverse array and prepare data for posting (key1=value1)
        foreach ( $post_data as $key => $value) {
            $post_items[] = $key . '=' . urlencode($value);
        }
        
        //create the final string to be posted using implode()
        $post_string = implode ('&', $post_items);
        
        return $post_string;
    }
  
}

