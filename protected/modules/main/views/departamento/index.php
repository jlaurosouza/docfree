<script type="text/javascript">

    function oDepartamentosCallback(json) {

        if (!$('#main #oDepartamentosoCallback').length)
        {
            $('#main').append('<div id="oDepartamentosoCallback"></div>');
        }

        $('#oDepartamentosoCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oDepartamentos
         */
        var oDepartamentos = $('#oDepartamentos').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {
                "sSearch": "Pesquisar todas as colunas:",
                "sEmptyTable": "Não há Departamento relacionados a pesquisa",
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/departamento/grid'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 10,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oDepartamentos', 3);
                        $('.customPage.oDepartamentos').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oDepartamentos').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oDepartamentos').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oDepartamentos').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oDepartamentos').fadeIn(500);
                    }

                    fnCallback(data);
                    oDepartamentosCallback(data);
                }, 'json');
            }

        });

        var oDepartamentosClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oDepartamentos thead input").keyup(function() {

            var that = this;
            clearTimeout(oDepartamentosClearTimeout);
            oDepartamentosClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oDepartamentos', 3);

                var oSettings = oDepartamentos.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oDepartamentos.fnDraw();
                oDepartamentos.fnFilter(that.value, oDepartamentos.oApi._fnVisibleToColumnIndex(oDepartamentos.fnSettings(), $("#oDepartamentos thead input").index(that)));

            }, 3000);

        });

        $("#oDepartamentos thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oDepartamentos thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oDepartamentos thead input").not(this).val('');
            }
        });
        $("#oDepartamentos thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oDepartamentos thead input").not(this).val(this.initVal);
            }
        });
        //oDepartamentos.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oDepartamentos'))
            {
                oTableCustomLoader('#oDepartamentos', 3);
                oDepartamentos.fnPageChange(_targetPage, true);
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
            Departamentos  
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" >    
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-5">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-archive"></i>
                Departamentos
                <span>> Lista dos Departamentos </span>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-7">           
            <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile " data-toggle="modal" href="<?= Yii::app()->createAbsoluteUrl('main/departamento/create'); ?>">
                <i class="fa fa-plus"></i>
                Adicionar Novo Departamento
            </a>             
        </div>       
    </div>
    <section id="widget-grid">   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Todos os departamentos</h2>				                    
            </header>                                  
            <div class="row" >
                <?php
                if ($_GET) {
                    if (isset($_GET['acao']) && $_GET['acao'] == "create") {
                        $msg = "Departamento cadastrado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "update") {
                        $msg = "Departamento atualizado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "inactivate") {
                        $msg = "Departamento inativado com sucesso!";
                    } elseif (isset($_GET['acao']) && $_GET['acao'] == "def") {
                        $erro = "<strong>Nada foi feito</strong>, falha ao inativar Departamento";
                    } elseif (isset($_GET['acao']) && empty($_GET['acao'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/Departamento/index'));
                    } else {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/Departamento/index'));
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
                <div id="msgWarningErro" class="alert alert-warning fade in" style="display: none;">
                    <button class="close" data-dismiss="alert"> × </button>
                    <i class="fa-fw fa fa-warning "></i>            
                    <strong>Atenção!</strong><br><br>
                    <span id="msgW"></span>
                </div>
                <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
                <table id="oDepartamentos" name="oDepartamentos" class="table table-striped responsive table-bordered" style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;">
                    <thead>
                        <tr>
                            <th  class="style-table-codigo">CÓDIGO</th>
                            <th>DEPARTAMENTOS</th>
                            <th class="style-table-acoes">AÇÕES </th>
                        </tr>
                        <tr class="second" style="display: none" >
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!-- FIM DA DATATABLE -->
                <br>
            </div>
        </div>
</div>
<script>
    function inativar(id) {
        $.SmartMessageBox({
            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
            content: "Deseja inativar o departamento?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $.smallBox({
                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
                    content: "<i>Estamos inativando o departamento, <br />Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 99999//4000
                });
                var url = '<?= Yii::app()->createAbsoluteUrl("main/departamento/inactivate") ?>';
                $.get(url, {id: id}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/departamento/index/acao/inactivate') ?>";
                    } else {
                        $("#divSmallBoxes").hide();
                        $("#msgW").html(data.msg);
                        $("#msgWarningErro").show();
                    }
                }, "json");
            }
        });
    }
</script>