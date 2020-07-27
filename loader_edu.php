<?php
// enquanto eu não conserto o autoloader....

// Classe de utilitarios
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Util.php';
// API do Pipedrive
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Pipedrive.v1.php';
    
// Controllers
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlAtletas.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlEventos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlAdmin.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlEquipes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlIngressos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlTransacoes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlProdutos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlUsuarios.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Controllers/CtlResultados.php';
    
// Models
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlEventos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlAtletas.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlPages.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlAdmin.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlTransacoes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlUsuarios.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlProdutos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlIngressos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlAcademias.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlAgendamentos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlInscricoes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCategorias.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlEquipes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlMailing.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlFiliais.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlTypeform.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlTypeformLog.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlTypeformCampos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCategoriasEventos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCompetidores.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlProvas.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlResultados.php';

// Views
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesErro.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesManutencao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesConfirmaMailing.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesLogin.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesSemPermissao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Pages/ViewPagesHome.php';
    
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosAvisoInscricao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosInscricao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosInscricaoIndisp.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosIngressos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosIngressosIndisp.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewEventosSucessoCompra.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Eventos/ViewLeaderBoardHTML.php';
    
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Transacoes/ViewTransacoesSucesso.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Transacoes/ViewTransacoesLista.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Transacoes/ViewTransacoesSucessoOperacao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Transacoes/ViewTransacoesCancelamento.php';
    
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminResumoInscricoes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminResumoIngressos.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminListaInscricoes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminTotalCamisas.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminListaInscricoesEq.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminVerificaInscricoesCielo.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Admin/ViewAdminAtualizaFotos.php';
    
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Atletas/ViewAtletasAtualizaCadastro.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Equipes/ViewEquipesAtualizaCadastro.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Ingressos/ViewIngressosConsulta.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Ingressos/ViewIngresso.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Produtos/ViewProdutosSniper.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Usuarios/ViewUsuariosCabecalhoHTML.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Usuarios/ViewUsuariosTrocaSenha.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Usuarios/ViewUsuariosEsqueceuSenha.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Usuarios/ViewUsuariosSucessoTrocaSenha.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Resultados/ViewResultadosPainelControle.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes/Views/Resultados/ViewResultadosInformaResultado.php';
    
// variáveis de ambiente
    include $_SERVER['DOCUMENT_ROOT'] . '/var.php';

