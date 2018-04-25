<style>
    /* ==== ERROR SUMERY ==== */
    .select i{
        display: none;
    }
    #s2id_ddl-associados{
        width: 292px!important;
    }
    /* ==== ERROR SUMERY ==== */
</style>
<script type="text/javascript">

    function cadastrarnovofuncionario() {

        var codigoassociado = $("#ddl-associados").val();
        if (codigoassociado == "") {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione a empresa associado!</span>",
                content: "Para cadastrar um novo funcionário é necessário selecionar uma empresa associada.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                }
            });
        } else {
            window.location = '<?= Yii::app()->createAbsoluteUrl("main/associadosfuncionario/create/ce/") ?>/' + codigoassociado;
        }
    }
    function oFuncionariosCallback(json) {

        if (!$('#main #oFuncionariosoCallback').length)
        {
            $('#main').append('<div id="oFuncionariosoCallback"></div>');
        }

        $('#oFuncionariosoCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oFuncionarios
         */
        var oFuncionarios = $('#oFuncionarios').dataTable({
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/associadosfuncionario/grid'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 30,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oFuncionarios', 5);
                        $('.customPage.oFuncionarios').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oFuncionarios').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oFuncionarios').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oFuncionarios').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oFuncionarios').fadeIn(500);
                    }

                    fnCallback(data);
                    oFuncionariosCallback(data);
                }, 'json');
            }

        });

        var oFuncionariosClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oFuncionarios thead input").keyup(function() {

            var that = this;
            clearTimeout(oFuncionariosClearTimeout);
            oFuncionariosClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oFuncionarios', 5);

                var oSettings = oFuncionarios.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oFuncionarios.fnDraw();
                oFuncionarios.fnFilter(that.value, oFuncionarios.oApi._fnVisibleToColumnIndex(oFuncionarios.fnSettings(), $("#oFuncionarios thead input").index(that)));

            }, 3000);

        });

        $("#oFuncionarios thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oFuncionarios thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oFuncionarios thead input").not(this).val('');
            }
        });
        $("#oFuncionarios thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oFuncionarios thead input").not(this).val(this.initVal);
            }
        });
        //oFuncionarios.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oFuncionarios'))
            {
                oTableCustomLoader('#oFuncionarios', 5);
                oFuncionarios.fnPageChange(_targetPage, true);
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
            Funcionários / Associados  
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" >    
    <div class="row">
        <!--<div class="col-xs-6 col-sm-6 col-md-6 col-lg-5">-->
        <div class="col col-md-4">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-group"></i>
                Funcionários
                <span>> Lista por Associados </span>
            </h1>
        </div>   
        <div class="row smart-form pull-right">   
            <label class='input-sm'>
                <section class="col col-lg-5" >
                    <label class ='label'>Empresas Associadas</label>
                    <label class="select"> <i class="icon-append fa"></i>
                        <?php
                        $criteria = new CDbCriteria();
                        $criteria->condition = "status=:status AND tipo=:tipo ORDER BY id DESC";
                        $criteria->params = array(":status" => "A", ":tipo" => "J");

                        $empresas = TbAssociado::model()->findAll($criteria);
                        $list = CHtml::listdata($empresas, "id", "nomerazao");
                        echo CHtml::dropDownList('ddl-associados', $list, $list, array('empty' => 'Selecione a empresa associada', 'onchange' => 'carregarFuncionariosEmpresa()', 'class' => 'select2'));
//                            echo CHtml::dropDownList('ddl-eventos', $list, $list, array('empty' => 'Selecione o evento', 'onchange' => 'carregarAtracoesEvento()', 'class' => 'select2'));
                        ?> 
                    </label>
                    <label>&nbsp;</label>
                </section>                
                <section class="col col-lg-6 ">                
                    <label class='input' style="top: 9px!important;">
                        <a class="btn btn-primary btn-lg header-btn hidden-mobile " data-toggle="modal" href="javascript:void(0)" onclick="cadastrarnovofuncionario()">
                            <i class="fa fa-plus"></i>
                            Adicionar Novo - Funcionário / Associado
                        </a>
                    </label>
                </section>
            </label>
        </div>
    </div>
    <section id="widget-grid">   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Todos os funcionários - por Associados</h2>				                    
            </header>                                  
            <div class="row" >
                <?php
                if ($_GET) {
                    if (isset($_GET['msg']) && $_GET['msg'] == "update") {
                        $msg = "Funcionário atualizado com sucesso!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "inactivate") {
                        $msg = "Funcionário inativado com sucesso!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "vie") {
                        $erro = "<strong>Impossível excluir</strong>, existem informações integradas ao funcionário!";
                    } elseif (isset($_GET['msg']) && $_GET['msg'] == "def") {
                        $erro = "<strong>Nada foi feito</strong>, falha ao deletar funcionários";
                    } elseif (isset($_GET['msg']) && empty($_GET['msg'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
                    } else {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
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
                <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
                <table id="oFuncionarios" name="oFuncionarios" class="table table-striped responsive table-bordered" style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;">
                    <thead>
                        <tr>
                            <th class="style-table-codigo">CÓDIGO</th>
                            <th>ASSOCIADO A</th>
                            <th>NOME</th>
                            <th>E-MAIL</th>                                                       
                            <th class="style-table-acoes">AÇÕES </th>
                        </tr>
                        <tr class="second" style="display: none" >
                            <td></td>
                            <!--td></td-->
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
        </div>
    </section>    
</div>
<script>
    function inativar(id) {

        $.SmartMessageBox({
            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
            content: "Deseja inativar o funcionário associado?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $.smallBox({
                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
                    content: "<i>Estamos inativando o funcionário associado, <br />Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 99999//4000
                });

                var url = '<?= Yii::app()->createAbsoluteUrl("main/associadosfuncionario/inactivate") ?>';
                $.get(url, {id: id}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = document.URL + '/msg/inactivate';
                    } else {

                        $("#msgW").html(data.msg);
                        $("#msgWarning").show();
                    }

                }, "json");
            }
        });
    }
</script>