<script type="text/javascript">
    function oPesquisaCallback(json) {

        if (!$('#main #oPesquisaoCallback').length)
        {
            $('#main').append('<div id="oPesquisaoCallback"></div>');
        }

        $('#oPesquisaoCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oPesquisa
         */
        var oPesquisa = $('#oPesquisa').dataTable({
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/servico/pesquisar/grid'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 10,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oPesquisa', 4);
                        $('.customPage.oPesquisa').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oPesquisa').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oPesquisa').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oPesquisa').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oPesquisa').fadeIn(500);
                    }

                    fnCallback(data);
                    oPesquisaCallback(data);
                }, 'json');
            }

        });

        /* ==== CARREGAR O GRID ATRAVEZ DA AÇÃO DO DROPDOWNLIST ==== */
        $("#txtPesquisa").keyup(function() {

            clearTimeout(oPesquisaClearTimeout);
            oPesquisaClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oPesquisa', 4);
                var oSettings = oPesquisa.fnSettings();
                for (iCol = 0;
                        iCol < oSettings.aoPreSearchCols.length;
                        iCol++) {
                    oSettings.aoPreSearchCols[iCol].sSearch = '';
                }

                oSettings.aoPreSearchCols[0].sSearch = $("#txtPesquisa").val();
                oPesquisa.fnDraw();
            }, 1000);
        });

        /* ==== FIM CARREGAR O GRID ATRAVEZ DA AÇÃO DO DROPDOWNLIST ==== */

        var oPesquisaClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oPesquisa thead input").keyup(function() {

            var that = this;
            clearTimeout(oPesquisaClearTimeout);
            oPesquisaClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oPesquisa', 4);

                var oSettings = oPesquisa.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oPesquisa.fnDraw();
                oPesquisa.fnFilter(that.value, oPesquisa.oApi._fnVisibleToColumnIndex(oPesquisa.fnSettings(), $("#oPesquisa thead input").index(that)));

            }, 3000);

        });

        $("#oPesquisa thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oPesquisa thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oPesquisa thead input").not(this).val('');
            }
        });
        $("#oPesquisa thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oPesquisa thead input").not(this).val(this.initVal);
            }
        });
        //oPesquisa.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oPesquisa'))
            {
                oTableCustomLoader('#oPesquisa', 4);
                oPesquisa.fnPageChange(_targetPage, true);
            }

        });
    });
    /* == Função visualizar == */
    function viewDocument(dialog) {
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById('dialogoverlay');
        var dialogbox = document.getElementById('dialogbox');
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH + "px";
        dialogbox.style.top = "10px";
        dialogbox.style.display = "block";
        document.getElementById('dialogboxhead').innerHTML = '';
        $('#dialogboxhead').append(' Visualizar documento <button style="left: ' + (winW / 2 - 240) + 'px;" id="btnfechar" type="button" class="btn btn-defalut" onclick="fecharDiag()">Fechar</button>');
        document.getElementById('dialogboxbody').innerHTML = '<iframe src="<?php echo Yii::app()->request->baseUrl; ?>/' + dialog + '" width="' + winW / 2 + '" height="' + (winH / 2 + 150) + '" ></iframe>';
    }
    function fecharDiag() {
        document.getElementById('dialogbox').style.display = "none";
        document.getElementById('dialogoverlay').style.display = "none";
    }


    /* == Função Carrega ddlTipodoc == */
    function carregarTipoDoc() {
        oTableZeroRecords('#oPesquisa', 4);

        $(".pesquisa").hide();
        $('.txtCust').remove();
        $('#campSpace').hide();
        $('#itens').hide();
        $('#itens1').hide();
        $("#ddlTipodoc").html("");
        $("#ddlTipodoc").append("<option value=''>Selecione um departamento.</option>");
        document.getElementById('ddlTipodoc').disabled = true;
        if ($("#ddlDepartamento option:selected").val() > 0) {
            document.getElementById('ddlTipodoc').disabled = false;
            $("#ddlTipodoc").html("");
            $("#ddlTipodoc").append("<option value='0'>carregando...</option>");
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/carregarTipodoc/') ?>", {codigoDepartamento: $("#ddlDepartamento option:selected").val()}, function(data) {
                $("#ddlTipodoc").html("");
                for (i = 0; i < data.length; i++) {
                    $("#ddlTipodoc").append("<option value='" + data[i].id + "'>" + data[i].tipodocumento + "</option>");
                }
            }, "json");
        }
    }
    /* == Fim Função == */

    function carregarCustomizacao() {

        oTableZeroRecords('#oPesquisa', 4);

        var idtipodoc = $("#ddlTipodoc").val();
        $(".pesquisa").hide();
        var url = "<?php echo Yii::app()->createAbsoluteUrl('servico/pesquisar/getdadostipodocumento/id'); ?>/" + idtipodoc;
        $.post(url, {}, function(data) {
            $(".pesquisa").show();
            var campos = "• ";
            for (var i = 0; i < data.length; i++) {
                
                if (i == data.length - 1) {
                    campos = campos + data[i].titulocampo;
                } else {
                    campos = campos + data[i].titulocampo + ",    • ";
                }
            }
            $("#campos").html(campos);
            $('#txtPesquisa').focus();
        }, "json");
    }

    /* == Função Selecionar Documento == */
    $(function() {
        $("#indexacao-form").validate({
            // Rules for form validation
            rules: {
                'ddlDepartamento': {
                    required: true,
                    minlength: 1,
                    maxlength: 255
                },
                'ddlTipodoc': {
                    required: true,
                    minlength: 1,
                    maxlength: 255
                }
            },
            // Messages for form validation
            messages: {
                'ddlDepartamento': {
                    required: '<label style="color: red">Escolha o departamento</label>',
                    SelectName: {valueNotEquals: ""}
                },
                'ddlTipodoc': {
                    required: '<label style="color: red">Escolha o tipo documental</label>',
                    SelectName: {valueNotEquals: ""}
                }
            },
            // Do not change code below
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            // Check if has error
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors)
                {
                    //$('#btn').attr('disabled', false).css('cursor', 'pointer');
                    //$('#loader-button').hide();
                }
            }
        });
    });
</script>
<h1 id="page-header">Pesquisar Documento</h1>
<div class="fluid-container">
    <!-- row-fluid -->
    <div class="row-fluid">
        <!-- new widget -->
        <div class="jarviswidget" id="widget-id-1">
            <header>
                <h2><i class="icon-search"></i>&nbsp; Pesquisa Rápida documental</h2>                           
            </header>
            <!-- wrap div -->
            <div>
                <!-- content goes here -->
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'home-form',
                    'htmlOptions' => array('class' => 'form-horizontal themed'),
                    'enableAjaxValidation' => false,
                ));
                ?>  
                <!-- viewDocument -->
                <div id="dialogoverlay"></div>
                <div id="dialogbox">
                    <div>
                        <div id="dialogboxhead"></div>
                        <div id="dialogboxbody"></div>
                        <!--div id="dialogboxfoot"></div-->
                    </div>
                </div>
                <!-- FIM -->
                <div class="widget-body no-padding">
                    <div class="form-actions">                                    
                        <button id="btnVoltar" class="btn medium btn-default" onclick="Voltar();" type="button"><i class="icon-share-alt"></i> Voltar</button>
                    </div>

                    <div class="inner-spacer">
                        <fieldset id="select-demo-js">                            
                            <div class="control-group">
                                <div class="fluid-container">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <label class="">Esconha o Departamento</label>
                                            <label class="select"> <i class="icon-append fa"></i>
                                                <?php
                                                $modelDepartamento = TbDepartamento::model()->findAll();
                                                $list = CHtml::listdata($modelDepartamento, "id", "departamento");
                                                ?>
                                                <?php echo CHtml::dropDownList('ddlDepartamento', '', $list, array('id' => 'ddlDepartamento', 'empty' => '••• Selecione o departamento desejado •••', 'onchange' => 'carregarTipoDoc()', 'class' => 'span12')); ?>
                                            </label>
                                        </div>
                                        <div class="span6">
                                            <label class="">Esconha o Tipo Documental</label>
                                            <label class="select"> <i></i>
                                                <?php
                                                $list = array();
                                                echo CHtml::dropDownList('ddlTipodoc', '', $list, array('id' => 'ddlTipodoc', 'empty' => 'Selecione um departamento.', 'disabled' => 'true', 'onchange' => 'carregarCustomizacao()', 'class' => 'span12'));
                                                ?> 
                                            </label>
                                        </div>
                                    </div>                                    
                                </div>                               
                                <div>&nbsp;</div>
                                <div class="fluid-container pesquisa" style="display: none">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="alert adjusted alert-info">
                                                <i class="cus-exclamation"></i>
                                                <strong>A pesquisa poder ser efetuada por:</strong><br><br>
                                                <span id="campos"></span>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="fluid-container pesquisa" style="display: none">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <label class="">Campo para Pesquisar</label>       
                                            <input name="txtPesquisa" tabindex="2" id="txtPesquisa" type="text" class="input-block-level" placeholder="Descreva aqui sua pesquisa" />
                                        </div>
                                        <!--                                        <div class="span2">
                                                                                    <label class="">&nbsp;</label>   
                                                                                    <button id="btnCadastro" type="button" onclick="GerarPesquisa();" class="btn medium btn-primary">
                                                                                        <i class="cus-magnifier"></i> Gerar Pesquisa                                           
                                                                                    </button>
                                                                                </div>                                       -->
                                    </div>
                                </div>
                            </div>
                            <!-- <button type="button" onclick="viewDocument('/docPDF/PEC241-2016.pdf')">Visualizar PDF</button>-->
                            <div class="control-group">
                                <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
                                <strong style="font-size: 20px!important;">&nbsp;<i class="icon icon-list "></i>&nbsp;RESULTADO DA PESQUISA </strong>
                                <table id="oPesquisa" name="oPesquisa" class="table table-striped responsive table-bordered" style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;">
                                    <thead>
                                        <tr>
                                            <th class="style-table-codigo">CÓDIGO</th>
                                            <th>NOME DO DOCUMENTO</th>
                                            <th>DOCUMENTO</th>
                                            <th class="style-table-four-acoes">AÇÕES </th>
                                        </tr>
                                        <tr class="second" style="display: none" >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <!-- FIM DA DATATABLE -->
                            </div>
                        </fieldset>                         
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    function Voltar() {
        history.go(-1);
    }

</script>