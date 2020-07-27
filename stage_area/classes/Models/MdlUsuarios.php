<?php
// Boiler
// MdlUsuarios.php: definiçao do modelo de Usuarios do site
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'] . '/connection.php');

class MdlUsuarios
{

    public $usuario;                    // mantém o último usuário manipulado
    public $listaUsuarios = array();    // mantém a última lista de usuários solicitada
    
    public $codErro;
    public $campoErro;
    public $msgErro;
    
    const  EMAIL_FROM_CADASTRO = 'boiler@boilerchannel.com';
    const  SENDGRID_TEMPLATE_CADASTRO = 'f0bcc86c-7b91-4945-b213-96ac4933e8be';
    const  SENDGRID_TEMPLATE_RESET_SENHA = 'f0bcc86c-7b91-4945-b213-96ac4933e8be';

    public function __construct() 
    {
        $this->usuario = new Usuario();
    }

    // *******************************************************************************************************
    // *** incluiUsuario
    // *******************************************************************************************************
    // ***
    // *** Inclui usuário a partir de um objeto usuario passado como parâmetro
    // ***    - gera exceções nas violações de constraints (login ou email duplicados, valores nulos)
    // ***    - em caso de sucesso, obtém ID_USUARIO gerado e atualiza o objeto usuario do modelo
    public static function incluiUsuario(Usuario $usuario, $enviaEmail = FALSE)
    {
        // obtém instância do banco de dados
        $db = Db::getInstance();

        // prepara query para inserção 
        $qryInsert = $db->prepare('INSERT INTO USUARIO (LOGIN_USUARIO, EMAIL_USUARIO, CPF_USUARIO, SENHA_USUARIO, DATA_EXP_SENHA, NOME_USUARIO, NIVEL_AUT_USUARIO, OBS_USUARIO)'
                                . ' VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )');
        
        // associa as variáveis
        $dataExp = $usuario->getDataExpiracaoSenha();
        $qryInsert->bind_param('ssssssis',
                                $usuario->getLoginUsuario(),
                                $usuario->getEmailUsuario(),
                                $usuario->getCpfUsuario(),
                                $usuario->getSenhaCryptUsuario(),
                                $dataExp->format("Y-m-d H:i:s"),
                                $usuario->getNomeUsuario(),
                                $usuario->getNivelAutUsuario(),
                                $usuario->getObsUsuario());
                                
        // executa insert   
        if ($qryInsert->execute() === TRUE) {      
            // obtém último auto_increment do banco e atualiza o objeto com o id criado
            $usuario->setIdUsuario(mysqli_insert_id($db));
        } else {
            // registra exceção
            error_log(PHP_EOL . date('Y-m-d H:i:s') . ' - Erro na inclusao do usuario', 3, getenv('LOG_FILE'));
            error_log(PHP_EOL . '                    - erro: ' . $qryInsert->error, 3, getenv('LOG_FILE'));               

            throw new exception ('Ocorreu um erro na operação com o Banco de Dados. Por favor, entre em contato com suporte@boilerchannel.com');
        }
        
        // envia email 
        if ($enviaEmail) {
            Util::enviaEmailTemplate(MdlUsuarios::EMAIL_FROM_CADASTRO, 
                                 $usuario->getEmailUsuario(), 
                                 MdlUsuarios::SENDGRID_TEMPLATE_CADASTRO, 
                                 array('%nome%' => $usuario->getNomeUsuario()));
        }
        
        // retorna usuário com o idUsuario atualizado
        return $usuario;
                                 
    }
    
    // *******************************************************************************************************
    // *** atualizaUsuario
    // *******************************************************************************************************
    // ***
    // *** 
    // ***    
    public static function atualizaUsuario(Usuario $usuario)
    {
        // obtém instância do banco de dados
        $db = Db::getInstance();

        // prepara query para inserção 
        $qryUpdate = $db->prepare('UPDATE USUARIO SET EMAIL_USUARIO=?, SENHA_USUARIO=?, CPF_USUARIO=?, DATA_EXP_SENHA=?, ' .
                                  '                   NOME_USUARIO=?, NIVEL_AUT_USUARIO=?, OBS_USUARIO=?' . 
                                  '             WHERE LOGIN_USUARIO=?');

        // associa as variáveis
        $dataExp = $usuario->getDataExpiracaoSenha();
        $qryUpdate->bind_param('sssssiss',
                                $usuario->getEmailUsuario(),
                                $usuario->getSenhaCryptUsuario(),
                                $usuario->getCpfUsuario(),
                                $dataExp->format("Y-m-d H:i:s"),
                                $usuario->getNomeUsuario(),
                                $usuario->getNivelAutUsuario(),
                                $usuario->getObsUsuario(),
                                $usuario->getLoginUsuario());
                                
        // executa update   
        if ($qryUpdate->execute() === FALSE) {
            // registra exceção
            error_log(PHP_EOL . date('Y-m-d H:i:s') . ' - Erro na atualizacao do atleta', 3, getenv('LOG_FILE'));
            error_log(PHP_EOL . '                    - erro: ' . $qryInsert->error, 3, getenv('LOG_FILE'));               

            throw new exception ('Ocorreu um erro na operação com o Banco de Dados. Por favor, entre em contato com suporte@boilerchannel.com');
        }
    }
    
    // *******************************************************************************************************
    // *** listaUsuarios
    // *******************************************************************************************************
    // ***
    // *** Retorna um array de Usuarios que atendam ao critério passado, com a possibilidade de
    // *** um parâmetro de limite para auxiliar na paginação
    // ***
    public static function listaUsuarios($criterio = null, $limit = null) {
        $db = Db::getInstance();
        $listaUsuarios = array();
      
        $query = 'SELECT * FROM USUARIO where 1=1 ' . (is_null($criterio) ? '' : 'and ' . $criterio);
        if (!is_null($limit)) {
            $query .= 'LIMIT ' . $limit;
        }

        try {
            $result = $db->query($query);
        } catch (Exception $e) {
            return null;
        }

        if ($result->num_rows == 0) {
            return array(0 => NULL);
        }
        
        // retorna um array com os valores
        while($usuario = $result->fetch_assoc()) {
            $dadosUsuario = new Usuario();
            $dadosUsuario->setLoginUsuario($usuario['LOGIN_USUARIO']);
            $dadosUsuario->setIdUsuario($usuario['ID_USUARIO']);
            $dadosUsuario->setCPFUsuario($usuario['CPF_USUARIO']);
            $dadosUsuario->setEmailUsuario($usuario['EMAIL_USUARIO']);
            $dadosUsuario->setSenhaCryptUsuario($usuario['SENHA_USUARIO']);
            $dadosUsuario->setNomeUsuario($usuario['NOME_USUARIO']);
            $dadosUsuario->setNivelAutUsuario($usuario['NIVEL_AUT_USUARIO']);
            $dadosUsuario->setObsUsuario($usuario['OBS_USUARIO']);
            $dadosUsuario->setDataExpiracaoSenha(date_create_from_format('Y-m-d H:i:s',$usuario['DATA_EXP_SENHA']));

            array_push($listaUsuarios, $dadosUsuario);       
        }

        return $listaUsuarios;
    }

    // *******************************************************************************************************
    // *** encontraUsuario
    // *******************************************************************************************************
    // ***
    // *** Retorna Usuário por idUsuario
    // ***    
    public static function encontraUsuario($idUsuario) {
        return (MdlUsuarios::listaUsuarios('ID_USUARIO = "' . $idUsuario . '"')[0]);
    }

    // *******************************************************************************************************
    // *** encontraUsuarioLogin
    // *******************************************************************************************************
    // ***
    // *** Retorna Usuário por Login (chave candidata)
    // ***    
    public static function encontraUsuarioLogin($login_usuario) {
        return (MdlUsuarios::listaUsuarios('LOGIN_USUARIO = "' . $login_usuario . '"')[0]);
    }

    // *******************************************************************************************************
    // *** encontraUsuarioEmail
    // *******************************************************************************************************
    // ***
    // *** Retorna Usuário por Email (chave candidata)
    // ***    
    public static function encontraUsuarioEmail($email) {
        return (MdlUsuarios::listaUsuarios('EMAIL_USUARIO = "' . $email . '"')[0]);
    }
    
    // *******************************************************************************************************
    // *** encontraUsuarioCPF
    // *******************************************************************************************************
    // ***
    // *** Retorna Usuário por Email (chave candidata)
    // ***    
    public static function encontraUsuarioCPF($cpf) {
        return (MdlUsuarios::listaUsuarios('CPF_USUARIO = "' . $cpf . '"')[0]);
    }
    
    // *******************************************************************************************************
    // *** histogramaUsuarios
    // *******************************************************************************************************
    // ***
    // *** Retorna contagem e soma de valor de dadosTransacao que atendam ao critério passado, com a possibilidade de
    // *** um parâmetro de indique um agrupamento
    // ***
    public static function histogramaUsuarios($criterio = null, $grupo = null) {
        $db = Db::getInstance();
        $histograma = array();
        
        $query = ' FROM USUARIO where 1=1 ' . (is_null($criterio) ? '' : 'and ' . $criterio);
        if (!is_null($grupo)) {
            $query = 'SELECT ' . $grupo . ', count(*) as qtd ' . $query . ' GROUP BY ' . $grupo;
        } else {
            $query = 'SELECT count(*) as qtd ' . $query;
        }

        try {
            $result =  $db->query($query);
        } catch (Exception $e) {
            return null;
        }
        
        if (is_null($result)) {
            return null;
        }

        while($linha = $result->fetch_row()) {
            if ($grupo === NULL) {
                $histograma[0] = array('qtd' => $linha[0]);
            } else {
                $histograma[$linha[0]] = array('qtd' => $linha[1]);
            }
        }
        return $histograma;
    }
    
    // *******************************************************************************************************
    // *** reinicializaSenhaUsuario
    // *******************************************************************************************************
    // ***
    // *** gera uma senha aleatória expirada e envia um email para o usuário
    // ***
    public static function reinicializaSenhaUsuario($usuario, $enviaEmail = FALSE) 
    {
        
        // atribui nova senha
        $novaSenha = MdlUsuarios::geraSenha();
        $usuario->setSenhaUsuario($novaSenha);
        
        // expira senha
        $usuario->expiraSenha();

        // atualiza usuario
        MdlUsuarios::atualizaUsuario($usuario);
        
        // envia email
        if ($enviaEmail) {
            Util::enviaEmailTemplate(MdlUsuarios::EMAIL_FROM_CADASTRO, 
                                 $usuario->getEmailUsuario(), 
                                 MdlUsuarios::SENDGRID_TEMPLATE_RESET_SENHA, 
                                 array('%nome%' => $usuario->getNomeUsuario()),
                                 array('%senha%'=> $novaSenha)
                                 );     
        }                         
    }
    
    public static function geraSenha($tamanho = 6) 
    {
        // gera senha aleatória
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $chars = $alpha . $alpha_upper . $numeric;
 
        $len = strlen($chars);
        $novaSenha = '';
 
        for ($i=0;$i<$tamanho;$i++) {
            $novaSenha .= substr($chars, rand(0, $len-1), 1);
        }

        return str_shuffle($novaSenha);
    }
    
}

class Usuario
{
    private $idUsuario;
    private $loginUsuario;
    private $emailUsuario;
    private $cpfUsuario;
    private $nomeUsuario;
    private $senhaUsuario;
    private $dataExpiracaoSenha;
    private $nivelAutUsuario;
    private $obsUsuario;

    // domínio do nivel de segurança
    const  AUT_USUARIO_ADM = 100;
    const  AUT_USUARIO_ADM_EVENTO = 200;
    const  AUT_USUARIO_HEAD_JUDGE   = 300;
    const  AUT_USUARIO_HEAD_COACH   = 350;
    const  AUT_USUARIO_JUDGE = 400;
    const  AUT_USUARIO_ATLETA = 500;
    const  AUT_USUARIO_VENDA = 600;
    const  AUT_USUARIO_PUBLICO = 1000;
    
    // prazo para expiração de senha (em dias)
    const  PRAZO_EXPIRACAO_SENHA = 'P720D';  // 2 anos em formato DateInterval

    public function __construct() 
    {
        $this->nivelAutUsuario = Usuario::AUT_USUARIO_PUBLICO;
    }
    
    public static function nomeNivelAutUsuario($nivelAutUsuario) 
    {
     
        $nomeNivelAutUsuario = array(Usuario::AUT_USUARIO_ADM => "Administrador",
                                     Usuario::AUT_USUARIO_ADM_EVENTO => "Administrador de Evento", 
                                     Usuario::AUT_USUARIO_HEAD_JUDGE => "Chefe dos Árbitros",
                                     Usuario::AUT_USUARIO_HEAD_COACH => "Head Coach de Box",
                                     Usuario::AUT_USUARIO_JUDGE => "Árbitro",
                                     Usuario::AUT_USUARIO_ATLETA => "Atleta",
                                     Usuario::AUT_USUARIO_VENDA => "Vendedor",
                                     Usuario::AUT_USUARIO_PUBLICO   => "Público");
        
        return $nomeNivelAutUsuario[$nivelAutUsuario];
    }
    
    // getters e setters
    
    // idUsuario
    public function getIdUsuario() 
    {
        return $this->idUsuario;
    }
    
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
    
    // loginUsuario
    public function getLoginUsuario()
    {
        return $this->loginUsuario;
    }
    
    public function setLoginUsuario($loginUsuario)
    {
        $this->loginUsuario = $loginUsuario; 
    }    
    
    // nomeUsuario
    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }
    
    public function setNomeUsuario($nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario; 
    }

    // emailUsuario
    public function getEmailUsuario()
    {
        return $this->emailUsuario;
    }
    
    public function setEmailUsuario($email)
    {
        $this->emailUsuario = $email;
    }
    
    // cpfUsuario
    public function getCPFUsuario()
    {
        return $this->cpfUsuario;
    }
    
    public function setCPFUsuario($parm)
    {
        $this->cpfUsuario = $parm;
    }

    // senhaUsuario
    public function comparaSenhaUsuario($senha) 
    {
        if (Util::hash_equals($this->senhaUsuario, crypt($senha, $this->senhaUsuario)))  {
            return true;
        } else {
            return false;
        }
    }

    public function getSenhaCryptUsuario() 
    {
        return $this->senhaUsuario;    
    }
    
    public function setSenhaCryptUsuario($senhaCrypt)
    {
        $this->senhaUsuario = $senhaCrypt;
    }
    
    public function setSenhaUsuario($senha)
    {
        // custo do processamento
        $cost = 10;

        // cria "sal" aleatório
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        // Prefixa informação sobre o hash no sal: 
        // "$2a$" = Algoritmo Blowfish
        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        // cria a senha
        $this->senhaUsuario = crypt($senha, $salt);
        $this->setObsUsuario($senha);
        
        // atualiza a data de expiração da senha
        $this->setDataExpiracaoSenha((new DateTimeImmutable())->add(new DateInterval(Usuario::PRAZO_EXPIRACAO_SENHA)));
    }

    public function expiraSenha() 
    {
        $this->setDataExpiracaoSenha((new DateTimeImmutable())->sub(new DateInterval('P1D')));
    }

    public function getDataExpiracaoSenha() 
    {
        return $this->dataExpiracaoSenha;
    }
    
    public function setDataExpiracaoSenha($dataExpiracaoSenha) 
    {
        $this->dataExpiracaoSenha = $dataExpiracaoSenha;
    }

    public function getNivelAutUsuario() 
    {
        return $this->nivelAutUsuario;
    }
    
    public function setNivelAutUsuario($nivelAutUsuario)
    {
        $this->nivelAutUsuario = $nivelAutUsuario;
    }
    
    public function getObsUsuario() 
    {
        return $this->obsUsuario;
    }
    public function setObsUsuario($obsUsuario) 
    {
        $this->obsUsuario = $obsUsuario;
    }
    
}