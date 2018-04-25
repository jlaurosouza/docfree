<style>
    .transparencia {
        background: rgb(0, 0,0) transparent;
        background: rgba(0, 0, 0, 0);
    }    
</style>
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
            $("#s2id_ddlTipodoc .select2-choice .select2-chosen").html("carregando...");
            //$("#ddlTipodoc").append("<option value='0'>carregando...</option>");
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/carregarTipodoc/') ?>", {codigoDepartamento: $("#ddlDepartamento option:selected").val()}, function(data) {
                $("#ddlTipodoc").html("");
                for (i = 0; i < data.length; i++) {
                    $("#ddlTipodoc").append("<option value='" + data[i].id + "'>" + data[i].tipodocumento + "</option>");
                }
                $("#s2id_ddlTipodoc .select2-choice .select2-chosen").html("••• Selecione o tipo documental desejado •••");
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

        /*
         * CONVERT DIALOG TITLE TO HTML
         * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
         */

        $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
            _title: function(title) {
                if (!this.options.title) {
                    title.html("&#160;");
                } else {
                    title.html(this.options.title);
                }
            }
        }));

    });
    /****** FUNÇÃO PARA CONTROLE DOS POP-UPS DO VISUALIZADOR DOCUMENTAL ******/
    function viewDocument(cam) {
        
        var dialogview = $("#viewDoc").dialog({
            autoOpen: false,
            width: 800,
            resizable: false,
            modal: true
        });
        
        $(".ui-dialog-titlebar").addClass("transparencia");
        $(".ui-dialog").addClass("transparencia");
        
        
        document.getElementById('doc-form').innerHTML = '<iframe src="<?php echo Yii::app()->request->baseUrl; ?>/' + cam + '" width="770" height="500" ></iframe>';

        dialogview.dialog("open");
    }
    /**** FIM ****/

    /****** FUNÇÃO PARA CONTROLE DOS POP-UPS DO DETALHAMENTO DOCUMENTAL ******/
    function detalhamento(id) {
        $("tagextra").remove("");
        var dialogDetalhe = $("#viewDetalhe").dialog({
            autoOpen: false,
            width: 800,
            resizable: false,
            modal: true
        });
        var url = "<?php echo Yii::app()->createAbsoluteUrl('servico/pesquisar/retornacampos/id'); ?>/" + id;
        $.post(url, {id: id}, function(data) {
            //alert(data.html);
            document.getElementById('detalhe-form').html = "";
            document.getElementById('detalhe-form').innerHTML = data.html;
        }, "json");
        dialogDetalhe.dialog("open");
    }
    /**** FIM ****/

</script>
<section id="widget-grid" class="">   
    <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-lg fa-search"></i> </span>
            <h2>
                Pesquisa Rápida documental       
            </h2>                                   
        </header>
        <div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'home-form',
                'htmlOptions' => array('class' => 'smart-form'),
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


            <!-- widget content -->
            <div class="widget-body no-padding">

                <!--<header>
                    Os campos com * são obrigatórios.
                </header>--> 

                <fieldset>
                    <div class="row">
                        <section class="col col-lg-6">
                            <label class="label">Escolha o Departamento</label>
                            <div class="input"> 
                                <?php
                                $modelDepartamento = TbDepartamento::model()->findAll();
                                $list = CHtml::listdata($modelDepartamento, "id", "departamento");
                                ?>
                                <?php
                                echo CHtml::dropDownList('ddlDepartamento', '', $list, array('id' => 'ddlDepartamento', 'empty' => '••• Selecione o departamento desejado •••',
                                    'onchange' => 'carregarTipoDoc()', 'class' => 'select2'));
                                ?>
                            </div>                                
                        </section>                        
                        <section class="col col-lg-6">
                            <label class="label">Escolha o Tipo documental</label>
                            <div class="input"> 
                                <?php
                                $list = array();
                                echo CHtml::dropDownList('ddlTipodoc', '', $list, array('id' => 'ddlTipodoc', 'empty' => 'Selecione um departamento.', 'disabled' => 'true', 'onchange' => 'carregarCustomizacao()', 'class' => 'select2'));
                                ?>
                            </div>                                
                        </section>                                               
                    </div>

                    <div class="row">
                        <section class="col col-sm-12">
                            <div class="pesquisa" style="display: none">
                                <div class="alert alert-info fade in">
                                    <i class="fa-fw fa fa-info-circle"></i>
                                    <strong>A pesquisa poder ser efetuada por:</strong><br><br>
                                    <span id="campos"></span>
                                </div>                                                                            
                            </div>
                        </section>
                    </div>
                    <div class="row">
                        <div class="pesquisa" style="display: none">
                            <section class="col col-lg-12">
                                <label class="label">Campo para Pesquisar *</label>
                                <label class="input"> <i class="icon-append fa fa-search"></i>
                                    <input name="txtPesquisa" tabindex="2" id="txtPesquisa" type="text" placeholder="Descreva aqui sua pesquisa" />
                                </label>

                            </section>
                        </div>
                    </div>
                    <!-- <button type="button" onclick="viewDocument('/docPDF/PEC241-2016.pdf')">Visualizar PDF</button>-->

                </fieldset>
            </div>            
            <div class="widget-body no-padding">
                <fieldset>
                    <strong class="text-success" style="font-size: 20px!important;">&nbsp;<i class="fa fa-table "></i>&nbsp;RESULTADO DA PESQUISA </strong>
                <section>
                    <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->                
                    <table id="oPesquisa" name="oPesquisa" class="table table-striped table-bordered smart-form">
                        <thead>
                            <tr>
                                <!--<th class="style-table-codigo">CÓDIGO</th>-->
                                <th class="">NOME DO DOCUMENTO</th>
                                <th class="">DOCUMENTO</th>
                                <th class="style-table-two-acoes">AÇÕES </th>
                            </tr>
                            <tr class="second" style="display: none" >
                                <!--<td></td>-->
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- FIM DA DATATABLE -->
                </section>
                    </fieldset>
            </div>
            <?php $this->endWidget(); ?>  
        </div>
    </div>
</section>

<!-- Visualizador do documento selecionado -->
<?php $this->renderPartial('exibirdoc', array()); ?>
<!-- Visualizador do documento selecionado -->

<!-- Visualizador do documento selecionado -->
<?php $this->renderPartial('exibirdetalhe', array()); ?>
<!-- Visualizador do documento selecionado -->