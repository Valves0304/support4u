<?php
// Boiler
// ViewAtletasAtualizaCadastro.php: definiçao da view de atualizacao do cadastro do atleta
// autor: Dudu Waghabi
// ---------------------------------------------------------------------------
// a view exibe um formulário para atualização ou inclusao do atleta
class ViewAtletasAtualizaCadastro 
{
    private $controllerModel;   // modelo modificado pelo controller

    public function __construct($model) 
    {
        $this->controllerModel = $model;
    }
    
    public function output() 
    {
        $output  = ViewUsuariosCabecalhoHTML::output();
        $output .= file_get_contents('classes/Views/htmlTemplates/cadastroAtleta.html');        
        $output .= file_get_contents('classes/Views/htmlTemplates/footerSemScrolling.html');
        
        // substituição de variáveis
        // estes campos são preenchidos de qualquer maneira porque pode ser um cadastro 
        // retornando após críticas
        $output = str_replace('{boilerLoginUsuario}', $this->controllerModel->usuario->getLoginUsuario(), $output);
        $output = str_replace('{boilerNomeAtleta}', $this->controllerModel->atleta->getNomeAtleta(), $output);
        $output = str_replace('{boilerEmailAtleta}', $this->controllerModel->atleta->getEmailAtleta(), $output);
        $output = str_replace('{boilerIdAtleta}', $this->controllerModel->atleta->getIdAtleta(), $output);
        $output = str_replace('{boilerCpfAtleta}', $this->controllerModel->atleta->getCpfAtleta(), $output);
        $output = str_replace('{boilerFacebookAtleta}', $this->controllerModel->atleta->getFacebookAtleta(), $output);
        $output = str_replace('{boilerInstagramAtleta}', $this->controllerModel->atleta->getInstagramAtleta(), $output);
        $output = str_replace('{boilerWhatsappAtleta}', $this->controllerModel->atleta->getWhatsappAtleta(), $output);
        $output = str_replace('{boilerSnapchatAtleta}', $this->controllerModel->atleta->getSnapchatAtleta(), $output);
        $output = str_replace('{boilerIndMailsletter}', $this->controllerModel->atleta->getIndMailsletter() == 'S' ? 'checked': '', $output);
        $output = str_replace('{boilerAlturaAtleta}', $this->controllerModel->atleta->getMedidasAtleta(Atleta::MEDIDA_ALTURA_ATLETA), $output);
        $output = str_replace('{boilerPesoAtleta}', $this->controllerModel->atleta->getMedidasAtleta(Atleta::MEDIDA_PESO_ATLETA), $output);
        $output = str_replace('{boilerChecked' . $this->controllerModel->atleta->getMedidasAtleta(Atleta::MEDIDA_TAM_CAMISA_ATLETA) . '}','checked', $output);
        $output = str_replace('{boilerTenis' . $this->controllerModel->atleta->getMedidasAtleta(Atleta::MEDIDA_TAM_TENIS_ATLETA) . '}', 'selected', $output);
        
        $output = str_replace('{boilerListaAcademias}', 
                              Util::createSelect(MdlAcademias::listaAcademias(), 'getIdAcademia', 'getNomeAcademia', $this->controllerModel->atleta->getIdAcademia()),
                              $output);

        // comportamento muda se for um novo usuário ou uma alteração 
        if ($this->controllerModel->atleta->getIdUsuario() === NULL) {
            $output = str_replace('{boilerAcaoAtleta}', 'incluiAtletaFormulario', $output);
            $output = str_replace('{boilerNomeSubmit}', 'Cadastra Atleta', $output);
            $output = str_replace('{boilerCaminhoImagem}', 'imagens/atletas/semImagem.png', $output);
        } else {
            $output = str_replace('{boilerDesabilita}', 'disabled', $output);
            $output = str_replace('{boilerEsconde}', 'hidden', $output);
            $output = str_replace('{boilerAcaoAtleta}', 'atualizaAtletaFormulario', $output);
            $output = str_replace('{boilerNomeSubmit}', 'Atualiza Atleta', $output);
            
            // foto do atleta: data ao final do nome para forçar o refresh da foto 
            if (file_exists($this->controllerModel->atleta->getNomeArquivoFotoAtleta())) {
                $output = str_replace('{boilerCaminhoImagem}', 
                                      $this->controllerModel->atleta->getNomeArquivoFotoAtleta() . '?' . Date('U'), 
                                      $output);
            } else {
                $output = str_replace('{boilerCaminhoImagem}', 'imagens/atletas/semImagem.png', $output);
            }
        }

        // próximos eventos
        $listaEventos = MdlEventos::listaEventos(' DT_INI_EVENTO > "' . date("Y-m-d H:i:s") . '"');
        if ($listaEventos[0] === NULL) {
            $outputProximosEventos .= 'Não há eventos próximos';
        } else {
            $outputProximosEventos = '';
            foreach ($listaEventos as $evento) {
                $outputProximosEventos .= file_get_contents('classes/Views/htmlTemplates/proximosEventos.html');
                $outputProximosEventos = str_replace('{idEvento}', $evento->getIdEvento(), $outputProximosEventos);
                $inscricao = MdlInscricoes::encontraInscricao($this->controllerModel->atleta->getIdAtleta(), $evento->getIdEvento());

                if ($inscricao === NULL) {
                    $outputProximosEventos = str_replace('{boilerSituacaoInscricao}', '', $outputProximosEventos);
                } else {
                    $categoriaEvento = MdlCategoriasEventos::encontraCategoriaEvento($inscricao->getIdCategoria(), $inscricao->getIdEvento());
                    $produto = MdlProdutos::encontraProduto($categoriaEvento->getIdProduto());
                    $outputProximosEventos = str_replace('{boilerMensagemInscricao}', $produto->getDescProduto(), $outputProximosEventos);
                    
                    if (MdlInscricoes::verificaSituacaoInscricao($inscricao, false) == Inscricao::INSCRICAO_REGULAR) {
                        $outputProximosEventos = str_replace('{boilerSituacaoInscricao}', 'Inscrição Concluída', $outputProximosEventos);
                        $outputProximosEventos = str_replace('{boilerClasseSituacaoInscricao}', 'inscricao-regular', $outputProximosEventos);

                        if (!($inscricao->getIdEquipe() === NULL)) {
                            $equipe = MdlEquipes::encontraEquipe($inscricao->getIdEquipe());                
                            if ($this->controllerModel->atleta->getIdAtleta() == $equipe->getIdAtletaResponsavel()) {
                                $outputProximosEventos = str_replace('{boilerLinkAcaoIncricao}', 
                                                         '<a href=boiler.php?c=CtlEquipes&action=cadastraEquipe&idEquipe=' . 
                                                         $inscricao->getIdEquipe() . '&idEvento=' . $inscricao->getIdEvento() .
                                                         '>Dados da Equipe</a>', $outputProximosEventos);
                            }
                        }

                    } elseif (MdlInscricoes::verificaSituacaoInscricao($inscricao, false) == Inscricao::INSCRICAO_PEND_CADASTRO) {
                        $outputProximosEventos = str_replace('{boilerSituacaoInscricao}', 'INSCRIÇÃO PENDENTE', $outputProximosEventos);
                        $outputProximosEventos = str_replace('{boilerClasseSituacaoInscricao}', 'inscricao-pendente', $outputProximosEventos);
                        $outputProximosEventos = str_replace('{boilerLinkAcaoIncricao}' 
                                                ,'<input class="form-control {estilo_erro99}" value="" name="senhaInscricao" placeholder="Código para Inscrição" type="text" >'
                                                .'<input type=hidden name=validaInscricao value=' . $inscricao->getIdEvento() . '>'
                                                , $outputProximosEventos);
                                                
                    } elseif (MdlInscricoes::verificaSituacaoInscricao($inscricao, false) == Inscricao::INSCRICAO_PEND_CADASTRO_EQUIPE) {
                        $outputProximosEventos = str_replace('{boilerSituacaoInscricao}', 'INSCRIÇÃO DA EQUIPE PENDENTE', $outputProximosEventos);
                        $outputProximosEventos = str_replace('{boilerClasseSituacaoInscricao}', 'inscricao-pendente', $outputProximosEventos);

                        $equipe = MdlEquipes::encontraEquipe($inscricao->getIdEquipe());                
                        if ($this->controllerModel->atleta->getIdAtleta() == $equipe->getIdAtletaResponsavel()) {
                            $outputProximosEventos = str_replace('{boilerClassLinkAcaoInscricao}', 'link-acao-inscricao', $outputProximosEventos);
                            $link = '<a href=boiler.php?c=CtlEquipes&action=cadastraEquipe&idEquipe=' . 
                                                     $inscricao->getIdEquipe() . '&idEvento=' . $inscricao->getIdEvento() .
                                                     '>Cadastre sua Equipe</a>';
                                                     
                            $output = str_replace('{mensagem_sucesso}', 'Atenção: ' . $link , $output);
                            $outputProximosEventos = str_replace('{boilerLinkAcaoIncricao}', $link, $outputProximosEventos);
                        }
                    }
                }
                
                $dateIni = DateTime::createFromFormat('Y-m-d H:i:s', $evento->getDtIniInscricao());
                $dateFim = DateTime::createFromFormat('Y-m-d H:i:s', $evento->getDtFimInscricao());
                if ($dateIni > new DateTime()) {
                    $outputProximosEventos = str_replace('{boilerMensagemInscricao}', 'Em breve', $outputProximosEventos);
                } elseif ($dateFim < new DateTime()) {
                    $outputProximosEventos = str_replace('{boilerMensagemInscricao}', 'Inscrições Encerradas', $outputProximosEventos);
                } else {
                    $outputProximosEventos = str_replace('{boilerMensagemInscricao}', 'Abertas!', $outputProximosEventos);
                    if ($inscricao === NULL) {
                        $outputProximosEventos = str_replace('{boilerClassLinkAcaoInscricao}', 'link-acao-inscricao', $outputProximosEventos);
                        $outputProximosEventos = str_replace('{boilerLinkAcaoIncricao}', 
                                                 '<a href=' . $evento->getLinkInscricaoEvento() . 
                                                 '>Inscreva-se</a>', $outputProximosEventos);
                    }
                } 
                
                $outputProximosEventos = str_replace('{boilerLinkAcaoIncricao}', '', $outputProximosEventos);

            }
        }
        $output = str_replace('{boilerProximosEventos}', $outputProximosEventos, $output);
        
        // prepara informações de erro
        if (!empty($this->controllerModel->erros)) {
            $msgErro = '';  
            foreach ($this->controllerModel->erros as $erro) {
                $output = str_replace('{estilo_erro' . $erro['campo'] . '}', 'has-error', $output);
                $msgErro .= '<li>' . $erro['msg'] . '</li>';                
            }
            $output = str_replace('{mensagem_erro}', $msgErro, $output);
            $output = str_replace('{boilerMostraErro}', 'true', $output);
            $output = str_replace('{mensagem_sucesso}', '', $output);
        } else {
            $output = str_replace('{mensagem_erro}', '', $output);
            $output = str_replace('{boilerMostraErro}', 'false', $output);
            if (!is_null($this->controllerModel->msgErro)) {
                $output = str_replace('{mensagem_sucesso}', $this->controllerModel->msgErro, $output);
            } else {
                $output = str_replace('{mensagem_sucesso}', '', $output);
            }
        }
        
        $output = str_replace('{versao}', getenv('VER'), $output);
        return $output;
    }
}
