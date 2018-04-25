<script type="text/javascript">
    function removerLogo(id) {
        $.SmartMessageBox({
            title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Remover Logomarca!</span>",
            content: "Deseja realmente remover a Logomarca?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $("#btnVolta").hide();
                $("#btnCadastro").hide();
                $("#btnRemover").hide();
                $("#loading").show();
                $.smallBox({
                    title: "Aguarde...",
                    content: "<i>Estamos removendo a Logomarca.<br/> Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 4000
                });
                var url = "<?php echo Yii::app()->createAbsoluteUrl('main/associados/removerlogo'); ?>";

                $.get(url, {id: id}, function(data) {

                    if (data.tipo == 'SUCESSO') {
                        $("#loading").hide();
                        $("#btnCadastro").show();
                        $("#btnVolta").show();
                        $(".logomarca").hide();
                        $.SmartMessageBox({
                            title: "<i class='fa fa-check txt-color-green' ></i><span> Sucesso!</span>",
                            content: 'Logomarca removida.',
                            buttons: '[Ok]'
                        }, function(ButtonPressed) {
                            window.location = "<?php echo Yii::app()->createAbsoluteUrl('main/associados/index'); ?>";
                        });
                    } else {
                        $.SmartMessageBox({
                            title: "<i class='fa fa-warningn'></i><span class='txt-color-orangeDark'> Falha no Processo!</span>",
                            content: data.msg,
                            buttons: '[Ok]'
                        });
                    }
                }, "json");
            }
        });
    }
    function oAssociadoCallback(json) {

        if (!$('#main #oAssociadooCallback').length)
        {
            $('#main').append('<div id="oAssociadooCallback"></div>');
        }

        $('#oAssociadooCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oAssociado
         */
        var oAssociado = $('#oAssociado').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {"sSearch": "Pesquisar todas as colunas:",
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
            "bSortCellsTop": true, "bServerSide": true, "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/associados/grid'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 10,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oAssociado', 7);
                        $('.customPage.oAssociado').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oAssociado').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oAssociado').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oAssociado').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oAssociado').fadeIn(500);
                    }

                    fnCallback(data);
                    oAssociadoCallback(data);
                }, 'json');
            }

        });

        var oAssociadoClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oAssociado thead input").keyup(function() {

            var that = this;
            clearTimeout(oAssociadoClearTimeout);
            oAssociadoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oAssociado', 7);

                var oSettings = oAssociado.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oAssociado.fnDraw();
                oAssociado.fnFilter(that.value, oAssociado.oApi._fnVisibleToColumnIndex(oAssociado.fnSettings(), $("#oAssociado thead input").index(that)));

            }, 3000);

        });

        $("#oAssociado thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oAssociado thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oAssociado thead input").not(this).val('');
            }
        });
        $("#oAssociado thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oAssociado thead input").not(this).val(this.initVal);
            }
        });
        //oAssociado.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oAssociado'))
            {
                oTableCustomLoader('#oAssociado', 7);
                oAssociado.fnPageChange(_targetPage, true);
            }

        });
    });
</script>
<!-- RIBBON -->
<div id="ribbon">

    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            Associados  
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" >    
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-5">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-briefcase"></i>
                Associados
                <span>> Lista dos Associados </span>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-7">           
            <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile " data-toggle="modal" href="<?= Yii::app()->createAbsoluteUrl('main/associados/createpj'); ?>">
                <i class="fa fa-plus"></i>
                Adicionar Novo - Pessoa Jurídica
            </a> 

            <a class="btn btn-primary btn-lg pull-left header-btn hidden-mobile " data-toggle="modal" href="<?= Yii::app()->createAbsoluteUrl('main/associados/createpf'); ?>">
                <i class="fa fa-plus"></i>
                Adicionar Novo - Pessoa Física
            </a> 
        </div>
    </div>
    <section id="widget-grid"  >   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Todos os associados</h2>				                    
            </header>
            <!--<div>-->  
            <div class="row" >
                <?php
                if ($_GET) {
                    if (isset($_GET['msg']) && $_GET['msg'] == "create") {
                        $msg = "Associado e usuário administrador cadastrado com sucesso!";
                        if (isset($_GET['user'])) {
                            $infoUser = "<strong>Usuário:</strong> &nbsp;" . $_GET['user'];
                            $infoPwd = "<strong>Senha:</strong> &nbsp;" . $_GET['pwd'];
                        }
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "update") {
                        $msg = "Associado atualizado com sucesso!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "inactivate") {
                        $msg = "Associado inativado com sucesso!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "vie") {
                        $erro = "<strong>Impossível excluir</strong>, existem informações integradas ao associado!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "def") {
                        $erro = "<strong>Nada foi feito</strong>, falha ao deletar associado";
                    } elseif (isset($_GET['msg']) && empty($_GET['msg'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
                    } else {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
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
                <?php if (isset($infoUser) && !empty($infoUser)) {
                    ?>
                    <div class="alert fade in alert-info">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa fa-fw fa-info-circle"></i>
                        <strong>Infomações sobre o usuário administrador!</strong><br><br>
                        <strong>ATENÇÃO: </strong>É importante atualizar a senha.<br><br>
                        <?php echo $infoUser; ?><br>
                        <?php echo $infoPwd; ?>
                    </div>                    
                <?php } ?>
                <!-- INICIO DA DATATABLE -->               
                <table id="oAssociado" class="table table-striped table-bordered smart-form " >
                    <thead>
                        <tr>
                            <th class="style-table-codigo">CÓDIGO</th>
                            <th class="style-table-text-min">NOME / RAZÃO SOCIAL</th>
                            <th>DOCUMENTO</th>
                            <th class="style-table-text-min">E-MAIL</th>                                            
                            <th>TIPO</th>
                            <th class="style-table-acoes" >LOGOMARCA</th>
                            <th class="style-table-tree-acoes">AÇÕES </th>
                        </tr>
                        <tr class="second" style="display: none" >
                            <td></td>
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
                <br/>
            </div>
            <!-- FIM DA DATATABLE -->
            <!--</div>-->
            <!-- widget div-->
        </div>
    </section>
</div>
<script>
    function inativar(id) {        
        $.SmartMessageBox({
            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
            content: "Deseja inativar o associado?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $.smallBox({
                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
                    content: "<i>Estamos inativando o associado, <br />Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 99999//4000
                });
                var url = '<?= Yii::app()->createAbsoluteUrl("main/associados/inactivate") ?>';
                $.get(url, {id: id}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/associados/index/msg/inactivate') ?>";
                    } else {

                        $("#msgW").html(data.msg);
                        $("#msgWarning").show();
                    }

                }, "json");
            }
        });
    }
</script>