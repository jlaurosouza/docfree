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
                    if (tipocampo === "TEXTO") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<label class='input'> <i class='icon-append fa fa-edit'></i>" +
                                "<input class='txtCust' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='text' maxlength='255' size='60'/>" +
                                "</label></section>");
                    } else if (tipocampo === "DATA") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<div class='form-group'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<div class='input-group'>" +
                                "<input id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' placeholder=' Selecione uma data' class='" + i + " datacustom span10 form-control datepicker hasDatepicker' type='date' ondown='DataCustom(" + i + ")' onclick='DataCustom(" + i + ")'>" +
                                "<span class='input-group-addon'><i class='fa fa-calendar'></i></span>" +
                                "</div>" +
                                "</div>" +
                                "</section>");
                    } else if (tipocampo === "SELECAO") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<label class='input'>" +
                                "<select id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' class='form-control with-search txtsel" + i + "'  onfocus='carregaSelecao(" + i + ")'>" +
                                "</select>\n\
                                </label></section>");
                    }
                } else {
                    $('#itens1').show();
                    var tipocampo = data[i].tipocampo;
                    if (tipocampo === "TEXTO") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<label class='input'> <i class='icon-append fa fa-edit'></i>" +
                                "<input class='txtCust' id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' type='text' maxlength='255' size='60'/>" +
                                "</label></section>");
                    } else if (tipocampo === "DATA") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<div class='form-group'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<div class='input-group'>" +
                                "<input id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' placeholder=' Selecione uma data' class='" + i + " datacustom span10 form-control datepicker hasDatepicker' type='date' ondown='DataCustom(" + i + ")' onclick='DataCustom(" + i + ")'>" +
                                "<span class='input-group-addon'><i class='fa fa-calendar'></i></span>" +
                                "</div>" +
                                "</div>" +
                                "</section>");
                    } else if (tipocampo === "SELECAO") {
                        $('#itens').append("<section class='col col-lg-3 txtCust'>" +
                                "<label class='label'>" + data[i].titulocampo + " *</label>" +
                                "<label class='input'>" +
                                "<select id='" + data[i].nomecampo + "' name='" + data[i].nomecampo + "' class='form-control with-search txtsel" + i + "'  onfocus='carregaSelecao(" + i + ")'>" +
                                "</select>\n\
                                </label></section>");
                    }
                }
            }
        }, "json");
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

    $(document).ready(function() {

        /* == Testar se Existe Documento Selecionado e se é Válido  == */
        var arquivo = document.getElementById("file");

        arquivo.addEventListener("change", function(event) {
            var nome = arquivo.files[0].name;

            var Extensao = nome.substring(nome.lastIndexOf('.') + 1);

            var extensoes_permitidas = new Array("PDF");

            if (!Util.compararExtensao(Extensao, extensoes_permitidas)) {
                $.SmartMessageBox({
                    title: "<i class='fa fa-exclamation-triangle txt-color-orangeDark'></i><span > Selecione um Documento válido!</span>",
                    content: "O Documento selecionado não é do tipo suportado.",
                    buttons: '[Ok]'
                }, function(ButtonPressed) {
                    if (ButtonPressed === "Ok") {
                        document.getElementById("inputArquivo").value = "";

                        $("#inputArquivo").removeClass("valid");
                        $(".input-file").removeClass("state-success");

                        $("em[for=inputArquivo]").show();

                        $("#inputArquivo").addClass("invalid");
                        $(".input-file").addClass("state-error");
                        $(".input span").addClass("state-error");
                    }
                });
            } else {
                $("#inputArquivo").removeClass("invalid");
                $(".input-file").removeClass("state-error");

                $("em[for=inputArquivo]").hide();

                $("#inputArquivo").addClass("valid");
                $(".input-file").addClass("state-success");
                $(".input span").addClass("state-success");
            }
        });
        /* == Fim do teste == */

        $("#nomedocumento").keyup(function() {
            $("#nomedocumento").val($("#nomedocumento").val().toUpperCase());
        });
       
        // Validation	
        $('#indexacao-form').validate({
            // Rules for form validation
            rules: {
                'inputArquivo': {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                'inputArquivo': {
                    required: 'Selecione um documento'
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
                    //$('#btn').attr('disabled',false).css('cursor','pointer');
                    //$('#loader-button').hide();
//                    $("#btnCadastro").hide();
//                $("#btnVolta").hide();
//                $("#loading").show();
//                $("#msgWarning").hide();
//                $("#msgSucesso").hide();
                }
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
                <span class="widget-icon"> <i class="fa fa-lg fa-clipboard"></i> </span>
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
                    <div id="msgWarning" class="alert alert-warning fade in" >
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-warning"></i>
                        <strong>Atenção!</strong><br><br>
                        <span><?php echo $form->errorSummary($model, ""); ?></span>
                    </div>                    
                <?php } ?>
                <?php if ($retorno == "SALVO") { ?>

                    <div id="msgSucesso" class="alert alert-success fade in">
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-check-circle"></i>
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
                        <header id="campSpace" style="display: none"><center><strong>CAMPOS DE CUSTOMIZAÇÕES</strong><br />Todos os Campos são obrigatórios</center></header>
                        &nbsp;
                        <div class="control-group msgCustom" style="display: none;" >
                            <div class="alert adjusted alert-warning">
                                <i class="cus-exclamation-octagon-fram"></i>
                                <strong>Atenção, Todos os campos são obrigadórios! <p><br /> &nbsp;&nbsp;&nbsp; Por favor preencha:</p></strong>
                                <span id='msg'></span>
                            </div>                    
                        </div>
                        <label class ="msgCustom">&nbsp;</label>
                        <div class="row" id="itens" style="display: none">
                        </div>
                        &nbsp;
                        <div class="row" id="itens1" style="display: none">
                        </div>
                    </fieldset>
                    <footer>                        
                        <button id="btnCadastro" type="submit" class="btn btn-primary">
                            <i class=" fa fa-check"></i>&nbsp; Cadastrar                            
                        </button>
                        <img id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" >
                        <button id="btnVolta" class="btn btn-default" onclick="voltar();" type="button"><i class=" fa fa-reply"></i>&nbsp; Voltar</button>
                    </footer>
                </div>
                <?php $this->endWidget(); ?>  
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>

    </section>
    <!-- end widget -->
</div>
<!-- END MAIN CONTENT -->
<script>

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

    function Voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/default/index';
    }

</script>