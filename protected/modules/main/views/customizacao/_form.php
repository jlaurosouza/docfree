<script type="text/javascript">

    function carregarTipoDocumento() {
        $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/getnometipodocumento/') ?>", {id: $("#ddl-tipodoc option:selected").val()}, function(data) {
            $(".lblNomeTipoDoc").html(data[0].nome);            
            $('#Ck-Campo').prop("checked", false);
            document.getElementById('TbCustomizacao_tipocampo').disabled = false;

            if (data[0].ordem == 0) {
                $(".CampoZero").show();
            } else {
                $(".CampoZero").hide();
            }
            if (data[0].qtd < 8) {
                $(".hdLimite").hide();
                $(".hdObrigatorio").show();
            } else {
                $(".hdObrigatorio").hide();
                $(".hdLimite").show();
            }
        }, "json");
    }


    function oCustomizacaoCallback(json) {

        if (!$('#main #oCustomizacaooCallback').length)
        {
            $('#main').append('<div id="oCustomizacaooCallback"></div>');
        }

        $('#oCustomizacaooCallback').html(json.modal);
    }



    $(document).ready(function() {

        /*
         * Aguardando Resolução oCustomizacao
         */
        var oCustomizacao = $('#oCustomizacao').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {
                "sSearch": "Pesquisar todas as colunas:",
                "sEmptyTable": "Não há campo de customização relacionados a pesquisa",
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/customizacao/gridcustomizacao/ce/0'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 30,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oCustomizacao', 3);
                        $('.customPage.oCustomizacao').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oCustomizacao').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oCustomizacao').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oCustomizacao').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oCustomizacao').fadeIn(500);
                    }

                    fnCallback(data);
                    oCustomizacaoCallback(data);
                }, 'json');
            }

        });
        /* ==== CARREGAR O GRID ATRAVEZ DA AÇÃO DO DROPDOWNLIST ==== */
        $("#ddl-tipodoc").change(function() {

            clearTimeout(oCustomizacaoClearTimeout);
            oCustomizacaoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#ddl-tipodoc', 3);
                var oSettings = oCustomizacao.fnSettings();
                for (iCol = 0;
                        iCol < oSettings.aoPreSearchCols.length;
                        iCol++) {
                    oSettings.aoPreSearchCols[iCol].sSearch = '';
                }

                oSettings.aoPreSearchCols[0].sSearch = $("#ddl-tipodoc").val();
                oCustomizacao.fnDraw();

            }, 1000);
        });
        /* ==== FIM CARREGAR O GRID ATRAVEZ DA AÇÃO DO DROPDOWNLIST ==== */

        var oCustomizacaoClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oCustomizacao thead input").keyup(function() {

            var that = this;
            clearTimeout(oCustomizacaoClearTimeout);
            oCustomizacaoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oCustomizacao', 3);

                var oSettings = oCustomizacao.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oCustomizacao.fnDraw();
                oCustomizacao.fnFilter(that.value, oCustomizacao.oApi._fnVisibleToColumnIndex(oCustomizacao.fnSettings(), $("#oCustomizacao thead input").index(that)));

            }, 3000);

        });

        $("#oCustomizacao thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oCustomizacao thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oCustomizacao thead input").not(this).val('');
            }
        });
        $("#oCustomizacao thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oCustomizacao thead input").not(this).val(this.initVal);
            }
        });
        //oCustomizacao.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oCustomizacao'))
            {
                oTableCustomLoader('#oCustomizacao', 3);
                oCustomizacao.fnPageChange(_targetPage, true);
            }

        });
//    });
//    $(function() {

        $("#TbCustomizacao_titulocampo").keyup(function() {
            $("#TbCustomizacao_titulocampo").val($("#TbCustomizacao_titulocampo").val().toUpperCase());
        });

        // Validation            
        $("#customizacao-form").validate({
            // Rules for form validation
            rules: {
                'TbCustomizacao[titulocampo]': {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                }
            },
            // Messages for form validation
            messages: {
                'TbCustomizacao[titulocampo]': {
                    required: 'Digite o título  do campo',
                    minlength: 'O título  do campo deve ter no mínimo 03 caracteres',
                    maxlength: 'O título  do campo não pode ultrapassar 30 caracteres'
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
            },
            submitHandler: function(form) {

                $("#btnCadastro").hide();
                $("#btnVoltar").hide();
                $("#loading").show();

                $("#msgSuccess").hide();
                $("#msgWarning").hide();

                var id = $("#ddl-tipodoc option:selected").val();
                var titulocampo = $("#TbCustomizacao_titulocampo").val();
                var tipocampo = $("#TbCustomizacao_tipocampo").val();

                var campoprincipal = 'N';

                if (document.getElementById('Ck-Campo')) {
                    if ($("#Ck-Campo").is(':checked')) {
                        campoprincipal = 'S';
                    }
                }

                var url = document.URL;

<?php if ($page == 'create') { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/customizacao/create'); ?>";
<?php } else { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/customizacao/update/ce/id/' . $_GET['id']); ?>";
<?php } ?>

                $.post(url, {id: id, titulocampo: titulocampo, tipocampo: tipocampo, campoprincipal: campoprincipal}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        $("#loading").hide();

                        $("#TbCustomizacao_titulocampo").val("");
                        $('#Ck-Campo').prop("checked", false);
                        document.getElementById('TbCustomizacao_tipocampo').disabled = false;
                        $("#msgWarning").hide();
                        $("#msgSuccess").show();
                        $("#btnCadastro").show();

                        carregarTipoDocumento();

                        clearTimeout(oCustomizacaoClearTimeout);
                        oCustomizacaoClearTimeout = setTimeout(function() {

                            oTableCustomLoader('#ddl-tipodoc', 3);
                            var oSettings = oCustomizacao.fnSettings();
                            for (iCol = 0;
                                    iCol < oSettings.aoPreSearchCols.length;
                                    iCol++) {
                                oSettings.aoPreSearchCols[iCol].sSearch = '';
                            }

                            oSettings.aoPreSearchCols[0].sSearch = $("#ddl-tipodoc").val();
                            oCustomizacao.fnDraw();

                        }, 1000);
                    } else {

                        $("#msgW").html(data.msg);

                        $("#loading").hide();
                        $("#msgWarning").show();
                        $("#btnCadastro").show();
                        $("#btnVoltar").hide();
                    }

                }, "json");

                return false;
            }
        });
    });
</script>
<!-- RIBBON -->
<div id="ribbon" class="smart-form" >
    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            <?php if ($page == 'create') { ?>
                Customização / Adicionar Novo            
            <?php } else { ?>
                Customização / Atualizar            
            <?php } ?>
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<!-- END RIBBON -->
<!-- MAIN CONTENT -->
<div id="content" >
    <div class="row">
        <!--<div class="col-xs-12 col-sm-9 col-md-9 col-lg-6">-->
        <div class="col col-lg-6">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-puzzle-piece"></i>
                Customização
                <span>> Lista das Customizações </span>
            </h1>
        </div>
        <div class="smart-form">   
            <section class="col col-lg-6" >
                <label class ='label'>Escolha o Tipo documental</label>
                <label class="select"> <i class="icon-append fa"></i>
                    <?php
                    $tipodoc = TbTipodocumento::model()->findAll(array('order' => 'nome ASC'));
                    $list = CHtml::listdata($tipodoc, "id", "nome");
                    echo CHtml::dropDownList('ddl-tipodoc', $list, $list, array('empty' => 'Selecione o tipo documental', 'onchange' => 'carregarTipoDocumento()', 'class' => 'select2'));
                    ?> 
                </label>                
            </section>                                
        </div>   
    </div>
    <section id="widget-grid"  >                                        
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-lg fa-puzzle-piece"></i> </span>                               
                <?php if ($page == 'create') { ?>                    
                    <h2> Novo Campo de Customização - Tipo Documental: <b class="lblNomeTipoDoc" style="color: #003bb3"></b> </h2>				                    
                <?php } else { ?>
                    <h2> Atualizar Campo de Customização - Tipo Documental: <b class="lblNomeTipoDoc" style="color: #003bb3"></b> </h2>				                    
                <?php } ?> 

            </header>           
            <!-- widget div-->
            <div>                  
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'customizacao-form',
                    'htmlOptions' => array('class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                ));
                ?>
                <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-warning"></i>
                    <strong>Atenção!</strong><br><br>
                    <span id="msg"></span>
                </div>  
                <div id="msgSuccess" class="alert alert-success fade in" style="display: none">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Sucesso!</strong><br><br>
                    <span>Campo de Customização Criado com Sucesso!</span>
                </div>  
                <div class="widget-body no-padding">
                    <!-- se total Campos < 8 -->
                    <header class="hdObrigatorio" >
                        <label class="text-danger">
                        <h3>Os campos com * são obrigatórios.</h3>
                        &nbsp;
                        <h1>OBS: O total limite de campos são oitos.</h1>
                        </label>
                    </header>                         
                    <!-- se total Campos = 8 -->
                    <header class="hdLimite" style="display: none;" >
                        <label  class="text-danger">Limite de campos já atingido para:</label>  <b class="lblNomeTipoDoc" style="color: #003bb3;"></b> 
                    </header>

                    <fieldset>
                        <!-- se total Campos < 8 -->
                        <div class="row hdObrigatorio">
                            <section class="col col-5">
                                <label class="label"><?php echo $form->labelEx($model, 'titulocampo'); ?></label>
                                <label class="input"> <i class="icon-append fa fa-file-text"></i>
                                    <?php echo $form->textField($model, 'titulocampo', array('size' => 60, 'maxlength' => 30)); ?>                                                                                                                      
                                    <!-- Verificar se já existe Campo principal -->
                                    <!-- se campo principal (ordem 0) não cadastrada  -->
                                    <div  class="form-inline CampoZero">
                                        <div class="checkbox">
                                            <label>
                                                <input class="checkbox style-0" id="Ck-Campo" name="Ck-Campo" type="checkbox" onchange="ChecandoCampo();">
                                                <span>Campo Principal: Referênte ao Nome do documento  </span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Fim da Verificação -->
                                </label>
                            </section>                       
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'tipocampo'); ?></label>
                                <label class="select"> <i></i>                                    
                                    <select id="TbCustomizacao_tipocampo" name="TbCustomizacao[tipocampo]" class="select2">
                                        <option value="TEXTO" />TEXTO LIVRE
                                        <option value="DATA" />DATA
                                        <option value="SELECAO" />SELEÇÃO
                                    </select>
                                </label>
                            </section>
                        </div>

                        <footer class="hdObrigatorio">                        
                            <button id="btnCadastro" onclick="validacao();" type="button" class="btn medium btn-primary">
                                <i class="fa fa-check"></i> 
                                <?php
                                if ($page == "create") {
                                    ?>
                                    Cadastrar
                                    <?php
                                } else {
                                    ?>
                                    Atualizar
                                    <?php
                                }
                                ?>
                            </button>
                            <img style="display: none" id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" >
                        </footer>
                        <br class="hdObrigatorio">
                        <section class="col-xs-12">
                            <label class="label"> <div class="text-success">
                                    <strong style="font-size: 20px!important;">&nbsp;<i class="fa fa-table"></i>&nbsp;LISTA DOS CAMPOS CADASTRADOS PARA: </strong><b class="lblNomeTipoDoc" style="color: #003bb3; font-size: 20px!important;"></b>  
                                </div>
                            </label>                            
                            <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->                                
                            <table id="oCustomizacao" name="oCustomizacao" class="table table-striped responsive table-bordered" >
                                <thead>
                                    <tr>
                                        <th class="style-table-codigo">ORDEM</th>
                                        <th >CAMPOS DE CUSTOMIZAÇÃO</th>   
                                        <th >TIPO DO CAMPOS</th>   
                                        <!--<th class="style-table-acoes">AÇÕES </th>-->
                                    </tr>
                                    <tr class="second" style="display: none" >                                            
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody></tbody> 
                            </table>
                        </section>
                    </fieldset>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </section>
</div>
<!-- END MAIN CONTENT -->
<script>
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/customizacao/index';
    }
    function ChecandoCampo() {
        if (document.getElementById('Ck-Campo')) {
            if ($("#Ck-Campo").is(':checked')) {
                document.getElementById('TbCustomizacao_tipocampo').disabled = true;
            } else {
                document.getElementById('TbCustomizacao_tipocampo').disabled = false;
            }
        }
    }
    function validacao() {
        var codigotipodoc = $("#ddl-tipodoc").val();
        if (codigotipodoc == "") {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione o tipo documental!</span>",
                content: "Para criar um novo campo customizado é necessário selecionar um tipo documental.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                }
            });
        } else {
            $('form').submit();
        }
    }
//    function inativar(id, ordem) {
//
//        $.SmartMessageBox({
//            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
//            content: "Deseja inativar o funcionário associado?",
//            buttons: '[Não][Sim]'
//        }, function(ButtonPressed) {
//            if (ButtonPressed === "Sim") {
//                $.smallBox({
//                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
//                    content: "<i>Estamos inativando o funcionário associado, <br />Este processo pode demorar um pouco</i>",
//                    color: "#3276B1",
//                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
//                    timeout: 99999//4000
//                });
//
//                var url = '<-?= Yii::app()->createAbsoluteUrl("main/associadosfuncionario/inactivate") ?>';
//                $.get(url, {id: id}, function(data) {
//
//                    if (data.tipo == "SUCESSO") {
//                        window.location = document.URL + '/msg/inactivate';
//                    } else {
//
//                        $("#msgW").html(data.msg);
//                        $("#msgWarning").show();
//                    }
//
//                }, "json");
//            }
//        });
//    }
</script>