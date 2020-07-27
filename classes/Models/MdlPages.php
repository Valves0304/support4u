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

/*    public static function nivelMinAutorizacaoAcesso($nomeController, $nomeAction = null)
    {
        $nivelMinAutorizacaoAcesso = array("CtlEventos" => array(NULL => NULL,
                                                                 "inscreve" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "avisoInscricao" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "compraIngresso" => Usuario::AUT_USUARIO_PUBLICO),
                                            "CtlProdutos"  => array(NULL => NULL,
                                                                 "sniper" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "compraSniper" => Usuario::AUT_USUARIO_PUBLICO),
                                            "CtlIngressos"  => array(NULL => NULL,
                                                                 "exibeIngresso" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "compraIngresso" => Usuario::AUT_USUARIO_PUBLICO),
                                            "CtlEquipes" => array(NULL, Usuario::AUT_USUARIO_ATLETA,
                                                                 "atualizaEquipeFormulario" => Usuario::AUT_USUARIO_ATLETA,
                                                                 "cadastraEquipe" => Usuario::AUT_USUARIO_ATLETA),
                                            "CtlPages"  => array(NULL => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "home" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "manutencao" => Usuario::AUT_USUARIO_ADM,
                                                                 "sobre" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "login" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "confirmaMailing" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "reinicializaSenha" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "semPermissao" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "erro" => Usuario::AUT_USUARIO_PUBLICO),
                                            "CtlTransacoes" => array(NULL => NULL,
                                                                 "realizaPagamento" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "realizaCancelamento" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "compraProduto" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "reenviaEmail" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "listaTransacoes" => Usuario::AUT_USUARIO_ADM_EVENTO),
                                            "CtlResultados" => array(NULL => NULL,
                                                                 "painelControle" => Usuario::AUT_USUARIO_ADM,
                                                                 "informaResultados" => Usuario::AUT_USUARIO_ADM),
                                            "CtlUsuarios" => array(NULL => NULL,
                                                                 "login" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "logout" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "esqueceuSenha" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "trocaSenha" => Usuario::AUT_USUARIO_VENDA,
                                                                 "reinicializaSenha" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "cadastraUsuario" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "listaUsuarios" => Usuario::AUT_USUARIO_ADM_EVENTO),
                                            "CtlAdmin" => array(NULL, NULL,
                                                                 "resumoInscricoes" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "resumoIngressos" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "listaInscricoes" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "totalCamisas" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "atualizaFotos" => Usuario::AUT_USUARIO_ADM,
                                                                 "consultaTransacaoCielo" => Usuario::AUT_USUARIO_ADM_EVENTO,
                                                                 "verificaInscricoesCielo" => Usuario::AUT_USUARIO_ADM_EVENTO),
                                            "CtlAtletas" => array(NULL, Usuario::AUT_USUARIO_PUBLICO,
                                                                 "atualizaAtletaFormulario" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "incluiAtletaFormulario" => Usuario::AUT_USUARIO_PUBLICO,
                                                                 "cadastraAtleta" => Usuario::AUT_USUARIO_PUBLICO)
                                    );
        return $nivelMinAutorizacaoAcesso[$nomeController][$nomeAction];
    }*/
}
