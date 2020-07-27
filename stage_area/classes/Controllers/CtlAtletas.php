<?php
// Boiler
// CtlAtletas.php: definiçao do controller dos atletas do site
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// - Ações do controller:
// - cadastraAtleta - cadastra o atleta
class CtlAtletas 
{
    // Atributos
    public $view;   // view escolhida pelo controller
    private $model; // modelo do controller, a ser modificado
    
    // Construtor
    public function __construct() 
    {
        $this->view = null;
        $this->model = new MdlAtletas();
    }
    
    // Métodos
    // Ações
    

    // *******************************************************************************************************
    // *** cadastraAtleta
    // *******************************************************************************************************
    // ***
    // *** Cadastra ou atualiza um atleta
    // ***        
    // *** Esta ação pode ser chamada em algumas situações
    // ***    - cadastro:     não informando parâmetros, um novo par atleta/usuário será criado, logo a página
    // ***                    de cadastro é exibida completamente em branco;
    // ***    - atualização:  informando um login de usuário, recupera-se o id de usuário e, através deste,
    // ***                    as demais informações para atualização; 
    // ***                    pode-se informar diretamente o id de atleta (apenas administradores).
    // ***
    public function cadastraAtleta() 
    {

        // recebe parâmetros
        $loginUsuario  = isset($_GET['usuario']) ? $_GET['usuario'] : FALSE;
        $idAtleta      = isset($_GET['idAtleta']) ? $_GET['idAtleta'] : FALSE;
        $indPreCadastro= isset($_GET['pre']) ? TRUE : FALSE;

        // condições de autorização 
        if (($loginUsuario AND $loginUsuario != $_SESSION['USUARIO']) OR              // apenas o próprio usuário pode realizar esta operação     
            ($idAtleta AND !($indPreCadastro) AND                                     // pode ser um pré-cadastro, ou então
             $_SESSION['NIVAUT'] > Usuario::AUT_USUARIO_ADM_EVENTO)) {                // apenas adms podem alterar por idAtleta
            $this->view = new ViewPagesSemPermissao();
            return;
        }

        // prepara o modelo
        $this->model->atleta = new Atleta();
        $this->model->usuario = new Usuario();
        
        // carrega informações atuais na tela
        if ($loginUsuario) {
            $this->model->atleta = MdlAtletas::encontraAtletaLogin($loginUsuario);
            $this->model->usuario = MdlUsuarios::encontraUsuarioLogin($loginUsuario);
        } 
        
        if ($idAtleta) {
            $this->model->atleta = MdlAtletas::encontraAtleta($idAtleta);
            if (!is_null($this->model->atleta) and !is_null($this->model->atleta->getIdUsuario())) {
                if ($indPreCadastro) {
                    // erro: usuário já cadastrado - envia para a tela de login
                    $this->view = new ViewPagesLogin(new MdlPages());
                    return;                    
                } else {
                    $this->model->usuario = MdlUsuarios::encontraUsuario($this->model->atleta->getIdUsuario());
                } 
            }
        }
        
        // caso não seja um novo atleta, e o atleta não for encontrado...
        if (($loginUsuario OR $idAtleta) AND ($this->model->atleta === NULL)) {
            throw new Exception ('Atleta não encontrado');
        } else {
            // prepara o acesso a foto do atleta, se houver
            $this->model->atleta->setNomeArquivoFotoAtleta();
        }

        // exibe informações para atualização/inclusão
        $this->view = new ViewAtletasAtualizaCadastro($this->model);
    }

    // *******************************************************************************************************
    // *** atualizaAtleta
    // *******************************************************************************************************
    // ***
    // *** critica informações do formulario para atualizar o BD
    // *** 
    // *** 
    public function atualizaAtletaFormulario() 
    {
        // esta função exige que o idAtleta seja fornecido
        if (!isset($_POST['idAtleta'])) {
            throw new Exception ('Atleta não informado');
        }
        // cria objetos que conterão os valores informados para a atualização do BD
        $atleta = new Atleta();
        $usuario= new Usuario();
        
        // identifica o atleta e usuário no BD através do idAtleta fornecido
        $atleta = MdlAtletas::encontraAtleta($_POST['idAtleta']);
        if ($atleta === NULL) {
            throw new Exception ('Atleta não encontrado');
        } else {
            // atualiza objeto com informações do formulario
            $atleta->setNomeAtleta($_POST['nome']);
            $atleta->setEmailAtleta(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $atleta->setIdAcademia($_POST['idAcademia']);
            $atleta->setFacebookAtleta($_POST['facebookAtleta']);
            $atleta->setInstagramAtleta($_POST['instagramAtleta']);
            $atleta->setWhatsappAtleta(preg_replace('/[^0-9]/','',$_POST['whatsappAtleta']));
            $atleta->setCPFAtleta(str_pad(preg_replace('/[^0-9]/','',$_POST['cpfAtleta']), 11, '0', STR_PAD_LEFT));
            $atleta->setSnapchatAtleta($_POST['snapchatAtleta']);
            $atleta->setMedidasAtleta($_POST['tamTenisAtleta'], Atleta::MEDIDA_TAM_TENIS_ATLETA);
            $atleta->setMedidasAtleta($_POST['pesoAtleta'], Atleta::MEDIDA_PESO_ATLETA);
            $atleta->setMedidasAtleta($_POST['alturaAtleta'], Atleta::MEDIDA_ALTURA_ATLETA);
            $atleta->setMedidasAtleta($_POST['tamCamisa'], Atleta::MEDIDA_TAM_CAMISA_ATLETA);
            $atleta->setIndMailsletter($_POST['indMailsletter'] == "S" ? "S" : "N");
            if (isset($_FILES['foto'])) { 
                $atleta->setNomeArquivoFotoAtleta(basename($_FILES['foto']['tmp_name']));
            } else {
                $atleta->setNomeArquivoFotoAtleta($atleta->getIdAtleta());
            }
        }
        
        // obtém dados de usuário
        $usuario = MdlUsuarios::encontraUsuario($atleta->getIdUsuario());
        $usuario->setNomeUsuario($atleta->getNomeAtleta());
        $usuario->setEmailUsuario($atleta->getEmailAtleta());

        // modifica o modelo para eventual chamada da view
        $this->model->usuario = $usuario;
        $this->model->atleta = $atleta;
        // crítica dos campos do formulário: modifica as variáveis globais msgErro e campoErro do modelo
        // caso retorne com erro, volta para a view do formulário exibindo a mensagem
        if ($this->validaFormularioAtleta($this->model->atleta)) {
            $this->view = new ViewAtletasAtualizaCadastro($this->model);
            $this->model->atleta->setNomeArquivoFotoAtleta();
            return;
        }

        // caso contrário, solicita ao modelo de Atletas que atualize o Atleta
        MdlAtletas::atualizaAtleta($this->model->atleta);
        
        // atualiza imagem do atleta
        $oldArquivoFoto = $this->model->atleta->getNomeArquivoFotoAtleta();
        $this->model->atleta->setNomeArquivoFotoAtleta();
        if (file_exists($oldArquivoFoto)) {
            rename($oldArquivoFoto, $this->model->atleta->getNomeArquivoFotoAtleta());
        }
        
        // solicita ao modelo de Usuarios que atualize o Usuário
        MdlUsuarios::atualizaUsuario($this->model->usuario);
        
        // atualiza o nome do usuário nas informações da sessão
        $_SESSION['NOME_USUARIO'] = $this->model->usuario->getNomeUsuario();

        // mensagem de sucesso = mensagem de erro sem campo
        $this->model->msgErro = 'Seus dados foram atualizados com sucesso!';
        $this->model->campoErro = null;
        
        // volta a tela de cadastro para exibição da mensagem de sucesso
        $this->view = new ViewAtletasAtualizaCadastro($this->model);

    }

    // *******************************************************************************************************
    // *** incluiAtleta
    // *******************************************************************************************************
    // ***
    // *** critica informações do formulario para incluir atleta/usuario no BD
    // *** 
    // *** 
    public function incluiAtletaFormulario() 
    {
        // nota: é possível que o atleta já exista, e o usuário, não (pré-cadastro)

        // identifica o atleta e usuário no BD através do idAtleta fornecido
        if ($_POST['idAtleta'] != "") {
            $this->model->atleta = MdlAtletas::encontraAtleta($_POST['idAtleta']);
        } else {
            $this->model->atleta = new Atleta();
        }

        // copia valores para os objetos para exibição
        $this->model->atleta->setNomeAtleta($_POST['nome']);
        $this->model->atleta->setEmailAtleta(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $this->model->atleta->setIdAcademia($_POST['idAcademia']);
        $this->model->atleta->setFacebookAtleta($_POST['facebookAtleta']);
        $this->model->atleta->setInstagramAtleta($_POST['instagramAtleta']);
        $this->model->atleta->setWhatsappAtleta(preg_replace('/[^0-9]/','',$_POST['whatsappAtleta']));
        $this->model->atleta->setCPFAtleta(str_pad(preg_replace('/[^0-9]/','',$_POST['cpfAtleta']), 11, '0', STR_PAD_LEFT));
        $this->model->atleta->setSnapchatAtleta($_POST['snapchatAtleta']);
        $this->model->atleta->setMedidasAtleta($_POST['tamTenisAtleta'], Atleta::MEDIDA_TAM_TENIS_ATLETA);
        $this->model->atleta->setMedidasAtleta($_POST['pesoAtleta'], Atleta::MEDIDA_PESO_ATLETA);
        $this->model->atleta->setMedidasAtleta($_POST['alturaAtleta'], Atleta::MEDIDA_ALTURA_ATLETA);
        $this->model->atleta->setMedidasAtleta($_POST['tamCamisa'], Atleta::MEDIDA_TAM_CAMISA_ATLETA);
        $this->model->atleta->setIndMailsletter($_POST['indMailsletter'] == "S" ? "S" : "N");

        $this->model->atleta->setNomeArquivoFotoAtleta(basename($_FILES['foto']['tmp_name']));

        $usuario= new Usuario();
        $usuario->setLoginUsuario($_POST['login']);
        $usuario->setNomeUsuario($this->model->atleta->getNomeAtleta());
        $usuario->setEmailUsuario($this->model->atleta->getEmailAtleta());
        $usuario->setCPFUsuario($this->model->atleta->getCPFAtleta());
        $this->model->usuario = $usuario;

        // crítica dos campos do formulário: modifica as variáveis globais msgErro e campoErro do modelo
        // caso retorne com erro, volta ao formulário
        if ($this->validaFormularioAtleta($this->model->atleta)) {
            $this->view = new ViewAtletasAtualizaCadastro($this->model);
            return;
        }
        // inclusão usuário e inclusão ou atualização do atleta

        // inclui usuário primeiro, pela geração de IdUsuario - chave estrangeira de atleta
        $usuario->setNivelAutUsuario(Usuario::AUT_USUARIO_ATLETA);
        $usuario->setSenhaUsuario($_POST['senha']);
        // solicita ao modelo de Usuarios que inclua o Usuário
        $this->model->usuario = MdlUsuarios::incluiUsuario($usuario, FALSE);

        // atualiza o atleta com o idUsuario gerado
        $this->model->atleta->setIdUsuario($this->model->usuario->getIdUsuario());

        // inclui ou modifica o atleta
        if ($this->model->atleta->getIdAtleta() === NULL) {
            MdlAtletas::incluiAtleta($this->model->atleta);
        } else {
            MdlAtletas::atualizaAtleta($this->model->atleta);
        }

        // atualiza imagem do atleta
        $oldArquivoFoto = $this->model->atleta->getNomeArquivoFotoAtleta();
        $this->model->atleta->setNomeArquivoFotoAtleta();
        if (file_exists($oldArquivoFoto)) {
            rename($oldArquivoFoto, $this->model->atleta->getNomeArquivoFotoAtleta());
        }

        // faz login do atleta
        $_SESSION['USUARIO'] = $this->model->usuario->getLoginUsuario();
        $_SESSION['NOME_USUARIO'] = $this->model->usuario->getNomeUsuario();
        $_SESSION['NIVAUT'] = $this->model->usuario->getNivelAutUsuario();        

        // mensagem de sucesso = mensagem de erro sem campo
        $this->model->msgErro = 'Seus dados foram atualizados com sucesso!';
        $this->model->campoErro = null;

        // prepara view 
        $this->view = new ViewAtletasAtualizaCadastro($this->model);

    }

    // *******************************************************************************************************
    // *** validaFormularioAtleta
    // *******************************************************************************************************
    // ***
    // *** valida os valores da variável $_POST marcando os campos $codErro e $msgErro do modelo
    // *** adequadamente
    // ***    
    private function validaFormularioAtleta($atleta) 
    {
        $this->model->campoErro = null;
        $this->model->msgErro = null;
        // ********************************************************************************************************
        // crítica dos campos (usuário pode ter desabilitado javascript)
        $erros = array();
        
        
        // se é uma inclusão, é preciso garantir unicidade de login e conformidade da senha
        if ($atleta->getIdUsuario() === NULL) {
            if (!isset($_POST['login']) || $_POST['login'] == '') {
                $erros[] = array('campo' => 2, 'msg' => 'Login precisa ser preenchido');
            } elseif (MdlUsuarios::encontraUsuarioLogin($_POST['login'])) {
                $erros[] = array('campo' => 2, 'msg' => 'O Login escolhido já está em uso por outro usuário');
            }
            
            if ($_POST['senha'] <> $_POST['confirma']) {
                $erros[] = array('campo' => 4, 'msg' => 'Senhas não conferem');
            } elseif (!(CtlUsuarios::validaSenha($_POST['senha']))) {
                $erros[] = array('campo' => 3, 'msg' => 'Senha inválida');
            }

        }

        // validação do campo "nome do atleta"
        if (!isset($_POST['nome']) || $_POST['nome'] == '') {
            $erros[] = array('campo' => 5, 'msg' => 'Nome do atleta precisa ser preenchido');
        }

        // validação da senha de inscrição, se houver
        if (isset($_POST['validaInscricao']) AND isset($_POST['senhaInscricao']) AND !is_null($atleta)) {
            $inscricao = MdlInscricoes::encontraInscricao($atleta->getIdAtleta(), $_POST['validaInscricao']);
            if ($inscricao->getSenhaInscricao() <> $_POST['senhaInscricao']) {
                $erros[] = array('campo' => 99, 'msg' => 'Código para inscrição no evento incorreto');
            }

            // precisa informar tamanho da camisa
            if (!isset($_POST['tamCamisa'])) {
                $erros[] = array('campo' => 9, 'msg' => 'Favor indicar o tamanho da camisa');
            }

        }
        

        // validação do campo "email" - precisa ser válido e único
        if (!isset($_POST['email']) || $_POST['email'] == '') {
            $erros[] = array('campo' => 6, 'msg' => 'Email do atleta precisa ser preenchido');
        } else {
            if (!filter_var($atleta->getEmailAtleta(), FILTER_VALIDATE_EMAIL)) {
                $erros[] = array('campo' => 6, 'msg' => 'Entre com um email válido');
            }
            
            $usuarioEmail = MdlUsuarios::encontraUsuarioEmail($_POST['email']);
            if (!is_null($usuarioEmail) and $usuarioEmail->getIdUsuario() <> $atleta->getIdUsuario() ) {
                $erros[] = array('campo' => 6, 'msg' => 'Email do atleta já utilizado');
            }
        }
        
        // validação do campo CPF - precisa ser válido e único
        
        if (!isset($_POST['cpfAtleta']) || $_POST['cpfAtleta'] == '') {
            $erros[] = array('campo' => 8, 'msg' => 'CPF do atleta precisa ser preenchido');
        } else {
            $cpf = str_pad(preg_replace('/[^0-9]/','',$_POST['cpfAtleta']), 11, '0', STR_PAD_LEFT);
            if (!(Util::validaCPF($cpf))) {
                $erros[] = array('campo' => 8, 'msg' => 'Entre com um número de CPF válido');
            }

            $atletaCPF = MdlAtletas::encontraAtletaCPF($cpf);
            if (!is_null($atletaCPF) and !is_null($atletaCPF->getIdUsuario())) {
                $usuarioCPF = MdlUsuarios::encontraUsuario($atletaCPF->getIdUsuario());
                if ($usuarioCPF->getLoginUsuario() <> $_SESSION['USUARIO'] ) {
                    $erros[] = array('campo' => 8, 'msg' => 'CPF do atleta já utilizado');
                }
            }
        }        

        // critica da imagem E atualização no diretório!! (mesmo em caso de erro)
        if (isset($_FILES['foto']) and $_FILES['foto']['error'] != UPLOAD_ERR_NO_FILE) {
            
            // testa se houve erro no uploda
            if ($_FILES['foto']['error']) {
                
                if ($_FILES['foto']['error'] == UPLOAD_ERR_INI_SIZE) {
                    $erros[] = array('campo' => 1, 'msg' => 'Erro no carregamento da imagem: arquivo muito grande');
                } else {
                    $erros[] = array('campo' => 1, 'msg' => 'Erro no carregamento da imagem');
                }
                
            } elseif ($_FILES['foto']['size'] > Atleta::MAX_TAMANHO_FOTO_ATLETA) {
                $erros[] = array('campo' => 1, 'msg' => 'Erro no carregamento da imagem: arquivo muito grande');
            } else {
                // upload ocorreu sem incidentes
                // a imagem será redimensionada e armazenada no diretório
                $fotoRedim = Util::resizeImage($_FILES['foto']['tmp_name'], $_FILES['foto']['type'], Atleta::LARGURA_FOTO_ATLETA, Atleta::ALTURA_FOTO_ATLETA);
                if ($fotoRedim === NULL or $atleta->setArquivoFotoAtleta($fotoRedim) === FALSE) {
                    $erros[] = array('campo' => 1, 'msg' => 'Erro no processamento da imagem');

                    // exclui eventual foto temporária e reestabelece nome correto para a foto do atleta
                    if (file_exists($atleta->getNomeArquivoFotoAtleta())) {
                        unlink($atleta->getNomeArquivoFotoAtleta());
                    }
                    $atleta->setNomeArquivoFotoAtleta($atleta->getIdAtleta());

                }
            }
        }
        
        $this->model->erros = $erros;
        if (is_null($erros[0])) {
            return 0;
        } else {
            return 1;
        }
    }

    // *******************************************************************************************************
    // *** listaAtletas
    // *******************************************************************************************************
    // ***
    // *** exibe todos os atletas que atendem ao critério passado
    // ***    
    public function listaAtletas() 
    {

        $this->model->listaAtletas = MdlAtletas::listaAtletas(null, null);
        $this->view = new ViewAtletasLista($this->model);    
        
    }
    

}
