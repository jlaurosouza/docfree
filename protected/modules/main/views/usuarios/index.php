<script type="text/javascript">

    function oUsuariosCallback(json) {

        if (!$('#main #oUsuariosoCallback').length)
        {
            $('#main').append('<div id="oUsuariosoCallback"></div>');
        }

        $('#oUsuariosoCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oUsuarios
         */
        var oUsuarios = $('#oUsuarios').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {
                "sSearch": "Pesquisar todas as colunas:",
                "sEmptyTable": "Não há Documentos relacionados a pesquisa",
                "sInfo": "",
                "sInfoEmpty": "",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sLengthMenu": "_MENU_",
                "sLoadingRecords": "Aguarde...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum resultado.",
                "oPaginate": {
                    "sFirst": "Primeira",
                    "sPrevious": "Anterior",
                    "sNext": "Próxima",
                    "sLast": "Última"
                },
            },
            "bSortCellsTop": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/usuarios/grid'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 10,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oUsuarios', 6);
                        $('.customPage.oUsuarios').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oUsuarios').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oUsuarios').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oUsuarios').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oUsuarios').fadeIn(500);
                    }

                    fnCallback(data);
                    oUsuariosCallback(data);
                }, 'json');
            }

        });

        var oUsuariosClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oUsuarios thead input").keyup(function() {

            var that = this;
            clearTimeout(oUsuariosClearTimeout);
            oUsuariosClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oUsuarios', 6);

                var oSettings = oUsuarios.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oUsuarios.fnDraw();
                oUsuarios.fnFilter(that.value, oUsuarios.oApi._fnVisibleToColumnIndex(oUsuarios.fnSettings(), $("#oUsuarios thead input").index(that)));

            }, 3000);

        });

        $("#oUsuarios thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oUsuarios thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oUsuarios thead input").not(this).val('');
            }
        });
        $("#oUsuarios thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oUsuarios thead input").not(this).val(this.initVal);
            }
        });
        //oUsuarios.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oUsuarios'))
            {
                oTableCustomLoader('#oUsuarios', 6);
                oUsuarios.fnPageChange(_targetPage, true);
            }

        });
    });

</script>
<!-- RIBBON -->
<div id="ribbon">
    <span class="ribbon-button-alignment"> 
        <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true">
            <i class="fa fa-refresh"></i>
        </span> 
    </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            Usuários  
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" >    
    <div class="row">
        <!--<div class="col-xs-6 col-sm-6 col-md-6 col-lg-5">-->
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-5">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-user"></i>
                Usuários
                <span>> Lista dos Usuários do sistema </span>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-7">           
            <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile " data-toggle="modal" href="<?= Yii::app()->createAbsoluteUrl('main/usuarios/create'); ?>">
                <i class="fa fa-plus"></i>
                Adicionar Novo Usuário
            </a>             
        </div>       
    </div>
    <section id="widget-grid">   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Todos os usuários - por Associados</h2>				                    
            </header>                                  
            <div class="row" >
                <?php
                if ($_GET) {
                    if (isset($_GET['acao']) && $_GET['acao'] == "create") {
                        $msg = "Usuário cadastrado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "update") {
                        $msg = "Usuário atualizado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "inactivate") {
                        $msg = "Usuário inativado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "vie") {
                        $erro = "<strong>Impossível excluir</strong>, existem informações integradas ao associado!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "def") {
                        $erro = "<strong>Nada foi feito</strong>, falha ao deletar associado";
                    } elseif (isset($_GET['acao']) && empty($_GET['acao'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/usuarios/index'));
                    } else {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/usuarios/index'));
                    }
                }
                ?>        
                <?php
                if (isset($erro) && !empty($erro)) {
                    ?>
                    <div id="msgWarning" class="alert alert-warning fade in">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa-fw fa fa-warning "></i>            
                        <strong>Atenção!</strong><br><br>
                        <?php echo $erro; ?>
                    </div>                                   
                    <?php
                } elseif (isset($msg) && !empty($msg)) {
                    ?>
                    <div class="alert alert-success fade in">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa-fw fa fa-check"></i>            
                        <?php echo $msg; ?>
                    </div>
                    <?php
                }
                ?>               
                <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;"--> 
                <table id="oUsuarios" name="oUsuarios" class="table table-striped responsive table-bordered" >
                    <thead>
                        <tr>
                            <th class="style-table-codigo">CÓDIGO</th>
                            <th>NOME</th>
                            <th>USUÁRIO</th>
                            <th>ASSOCIADO A</th>
                            <th>NÍVEL DE ACESSO</th>
                            <th class="style-table-acoes">AÇÕES </th>                                                       
                        </tr>
                        <tr class="second" style="display: none" >
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
                <!-- FIM DA DATATABLE -->
            </div>
        </div>
    </section>
</div>
<script>
    function inativar(id) {        
        $.SmartMessageBox({
            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
            content: "Deseja inativar o usuário?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $.smallBox({
                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
                    content: "<i>Estamos inativando o usuário, <br />Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 99999//4000
                });
                var url = '<?= Yii::app()->createAbsoluteUrl("main/usuarios/inactivate") ?>';
                $.get(url, {id: id}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/usuarios/index/acao/inactivate') ?>";
                    } else {
                        $("#msgW").html(data.msg);
                        $("#msgWarning").show();
                    }

                }, "json");
            }
        });
    }
</script>