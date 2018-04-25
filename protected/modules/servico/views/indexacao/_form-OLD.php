<script type="text/javascript">
    /* == Função Carrega Campos Customizados == */
    function carregaSelecao(id) {
        var camporef = $(".txtsel" + id).attr('id');
        var val = document.getElementById(camporef).value;
        if (val == '') {
            var idtipodoc = $("#ddlTipodoc").val();
            $(".txtsel" + id).html("");
            $(".txtsel" + id).append("<option value='0'>carregando...</option>");
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/carregarCampoSel/') ?>", {idtipodoc: idtipodoc, camporef: camporef}, function(data) {
                $(".txtsel" + id).html("");
                for (ii = 0; ii < data.length; ii++) {
                    $(".txtsel" + id).append("<option value='" + data[ii].id + "'>" + data[ii].nomechave + "</option>");
                }
            }, "json");
        }
    }

    function carregarCustomizacao() {
        var idtipodoc = $("#ddlTipodoc").val();
        $('.msgCustom').hide();
        var url = "<?php echo Yii::app()->createAbsoluteUrl('main/default/carregarcustomizacao/idtipodoc'); ?>/" + idtipodoc;
        $('.txtCust').remove();
        $.get(url, function(data) {
            for (i = 0; i < data.length; i++) {
                $('#campSpace').show();
                $('#itens').show();
                if (i < 4) {
                    var tipocampo = data[i].tipocampo;
                    if (tipocampo === "DATA") {
                        $('#itens').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                "<input class='span12' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='text' maxlength='255' size='60'/>" +
                                "</div>");
                    } else if (tipocampo === "TEXTO") {
                        $('#itens').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                //"<div class='input-append span12'>" +
                                "<div class='input-append'>" +
                                "<input class='" + i + " datacustom span10' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='date' placeholder='Selecione uma data' readonly='' ondown='DataCustom(" + i + ")' onclick='DataCustom(" + i + ")' size='60'/>" +
                                "<span class='add-on' onclick='DataCustom(" + i + ")' ><i class='cus-calendar-2'></i></span></div></div>");
                    } else if (tipocampo === "SELECAO") {
                        $('#itens').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                "<select id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' class='span12 with-search txtsel" + i + "'  onfocus='carregaSelecao(" + i + ")'>" +
                                "</select></div>");
                    }
                } else {
                    $('#itens1').show();
                    var tipocampo = data[i].tipocampo;
                    if (tipocampo === "TEXTO") {
                        $('#itens1').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                "<input class='span12' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='text' maxlength='255' size='60'/>" +
                                "</div></div>");
                    } else if (tipocampo === "DATA") {
                        $('#itens1').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                "<div class='input-append'>" +
                                "<input class='" + i + " datacustom span10' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='date' placeholder='Selecione uma data' readonly='' ondown='DataCustom(" + i + ")' onclick='DataCustom(" + i + ")' size='60'/>" +
                                "<span class='add-on' onclick='DataCustom(" + i + ")' ><i class='cus-calendar-2'></i></span></div></div>");
                    } else if (tipocampo === "SELECAO") {
                        $('#itens1').append("<div class='span3 txtCust'> " +
                                "<label class=''>" + data[i].titulocampo + " *</label>" +
                                "<select id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' class='span12 with-search txtsel" + i + "' onfocus='carregaSelecao(" + i + ")'>" +
                                "</select></div>");
                    }
                }
            }
        }, "json");
    }

    /* == Função Carrega ddlTipodoc == */
    function carregarTipoDoc() {
        $('.msgCustom').hide();
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
    /* == Função Selecionar Documento == */
    $(function() {

        $("#nomedocumento").keyup(function() {
            $("#nomedocumento").val($("#nomedocumento").val().toUpperCase());
        });

        var div = document.getElementsByClassName("botaoArquivo")[0];
        var input = document.getElementById("inputArquivo");

        div.addEventListener("click", function() {
            input.click();
        });
        //input.hide();
        input.addEventListener("change", function() {
            var nome = "Não há arquivo selecionado. Selecionar arquivo...";
            if (input.files.length > 0) {
                nome = input.files[0].name;

                var Extensao = nome.substring(nome.lastIndexOf('.') + 1);

                var extensoes_permitidas = new Array("PDF");

                if (!Util.compararExtensao(Extensao, extensoes_permitidas)) {
                    $.SmartMessageBox({
                        title: "<i class='icon-exclamation-sign txt-color-orangeDark'></i><span > Selecione um Documento válido!</span>",
                        content: "O Arquivo selecionado não é do tipo suportado.",
                        buttons: '[Ok]'
                    }, function(ButtonPressed) {
                        if (ButtonPressed === "Ok") {
                            $('#placeholder').text("Nenhuma Documento selecionada.");
                            document.getElementById("inputArquivo").value = "";
                        }
                    });
                } else {
                    $('#placeholder').text(nome);
                }
            }
            /*fim */
        });
    });
    /* == Fim da Função Selecionar Documento */
//    setTimeout(function() {
//        $(document).ready(function() {
//            $('.datacustom').datepicker({
//                format: 'dd/mm/yyyy',
//                language: 'pt-BR'
//            });
//        });
//    }, 1);
    //$(document).ready(function() {
//    $(".datacustom").click(function(){
//        alert("entrou");
//        $(".datacustom").format("dd/mm/yyyy");
//    }); 
//        $(".datacustom").clck(function(){
//            alert($(".datacustom").val());
//        });
    //});
</script>
<!-- RIBBON -->
<div id="ribbon">

    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            Novo Documento / Adicionar Novo                                 
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<!-- END RIBBON -->
<!-- MAIN CONTENT -->
<div id="content">
    <section id="widget-grid" class="">                                        

        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <h2>
                    Adicionar Novo Documento
                </h2>				                    
            </header>
            <!-- widget div-->
            <div>
                <!-- content goes here -->                
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'indexacao-form',
                    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                    'enableAjaxValidation' => false,
                ));
                ?>                  
                <?php if ($form->errorSummary($model)) { ?>
                    <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-warning"></i>
                        <strong>Atenção!</strong><br><br>
                        <span><?php echo $form->errorSummary($model, ""); ?></span>
                    </div>                    
                <?php } ?>
                <?php if ($retorno == "SALVO") { ?>
                    <div id="msgSucesso" class="alert alert-success fade in" style="display: none">
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-success"></i>
                        <strong>Sucesso!</strong><br><br>
                        <span>Documento Cadastrado com Sucesso.</span>
                    </div>                      
                <?php } ?>

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <header >
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header> 
                    <fieldset>
                        <div class="row">
                            <section class="col col-lg-12">
                                <label class="label" for="inputArquivo">Selecione o Documento *</label>
                                <div class="input input-file ">
                                    <span class="button"><?php echo CHtml::fileField('file', '', array('id' => 'file', 'onchange' => "this.parentNode.nextSibling.value = this.value")); ?>Selecione um Documento</span><input id="inputArquivo" name="inputArquivo" type="text" readonly>
                                </div>
                                <div class="note">Apenas documento do tipo PDF é suportado.</div>
                            </section>                                               
                        </div>
                        <div class="row">
                            <section class="col col-lg-6">
                                <label class="label">Departamento *</label>
                                <label class="input">
                                    <?php
                                    $modelDepartamento = TbDepartamento::model()->findAll();
                                    $list = CHtml::listdata($modelDepartamento, "id", "departamento");
                                    ?>
                                    <?php
                                    echo CHtml::dropDownList('ddlDepartamento', '', $list, array('id' => 'ddlDepartamento', 'empty' => '••• Selecione o departamento desejado •••',
                                        'onchange' => 'carregarTipoDoc()', 'class' => 'select2'));
                                    ?>
                                </label>                                
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
                        <div class="invoice-client-info" id="campSpace" style="display: none"><center><strong>CAMPOS DE CUSTOMIZAÇÕES</strong><br />Todos os Campos são obrigatórios</center></div>
                                        &nbsp;
                                        <div class="control-group msgCustom" style="display: none;" >
                                            <div class="alert adjusted alert-warning">
                                                <i class="cus-exclamation-octagon-fram"></i>
                                                <strong>Atenção, Todos os campos são obrigadórios! <p><br /> &nbsp;&nbsp;&nbsp; Por favor preencha:</p></strong>
                                                <span id='msg'></span>
                                            </div>                    
                                        </div>
                                        <label class ="msgCustom">&nbsp;</label>
                                        <div class="row-fluid" id="itens" style="display: none">
                                        </div>
                                        &nbsp;
                                        <div class="row-fluid" id="itens1" style="display: none">
                                        </div>
                                    </div>                                    
                                </div>                               
                                <div>&nbsp;</div>
                    </fieldset>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </section>
</div>
<script>
    /* == Função Carrega Campos Customizados == */
    function Voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/default/index';
    }
    function DataCustom(id) {
        $('.datacustom').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
        var classe = id;
        $("." + classe).datepicker();
        $('.datacustom').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
        $("." + classe).focus();
    }
    function validaCampo() {

        $("#btnVoltar").hide();
        $("#btnCadastro").hide();
        $("#loading").show();

        var input = document.getElementById("inputArquivo").value;
        var ddlDep = document.getElementById("ddlDepartamento").value;
        var ddlTipoDoc = document.getElementById("ddlTipodoc").value;
        if (input == "") {
            $.SmartMessageBox({
                title: "<i class='icon-exclamation-sign txt-color-orangeDark'></i><span > Documento Inválido!</span>",
                content: "Selecione um Documento.",
                buttons: '[Fechar]'
            }, function(ButtonPressed) {
                $("#loading").hide();
                $("#btnVoltar").show();
                $("#btnCadastro").show();
            });
        } else if (ddlDep == 0) {
            $.SmartMessageBox({
                title: "<i class='icon-exclamation-sign txt-color-orangeDark'></i><span > Departamento inválido!</span>",
                content: "Selecione um Departamento.",
                buttons: '[Fechar]'
            }, function(ButtonPressed) {
                $("#loading").hide();
                $("#btnVoltar").show();
                $("#btnCadastro").show();
                $("#ddlDepartamento").focus();
            });
        } else if (ddlTipoDoc == 0) {
            $.SmartMessageBox({
                title: "<i class='icon-exclamation-sign txt-color-orangeDark'></i><span > Tipo Documental inválido!</span>",
                content: "Selecione um Tipo documental.",
                buttons: '[Fechar]'
            }, function(ButtonPressed) {
                $("#loading").hide();
                $("#btnVoltar").show();
                $("#btnCadastro").show();
                $("#ddlTipodoc").focus();
            });
        } else {
            var idtipodoc = $("#ddlTipodoc").val();

            if (idtipodoc > 0) {
                var url = "<?php echo Yii::app()->createAbsoluteUrl('main/default/dadoscustomizacao'); ?>/";
                var valor = '';
                var retorno = '<ul>';
                var erro = "0";
                $.post(url, {idtipodoc: idtipodoc}, function(data) {
                    for (i = 0; i < data.length; i++) {
                        valor = document.getElementById(data[i].nomecampo).value;
                        if (valor == "") {
                            erro = "1";
                            retorno = retorno + "<li>" + data[i].titulocampo + "</li>";
                        }
                    }
                    retorno = retorno + "</ul>";

                    if (erro == "1") {
                        $("#loading").hide();
                        $("#btnVoltar").show();
                        $("#btnCadastro").show();
                        $(".msgCustom").show();
                        $("#msg").html(retorno);
                    } else {
                        document.forms[0].submit();
                    }
                }, "json");
            }

        }
    }
</script>