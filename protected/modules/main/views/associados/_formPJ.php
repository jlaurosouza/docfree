<script type="text/javascript">

    function carregarCidades() {

        if ($("#TbAssociado_idestado option:selected").val() > 0) {
            $("#TbAssociado_idcidade").html("");
            $("#Cidade_estado").val($("#TbAssociado_idestado option:selected").val());
            $("#Descricao_estado").val($('.ddlestado .select2-choice .select2-chosen').text());
            $(".ddlcidade .select2-choice .select2-chosen").html("carregando...");
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/carregarCidades/') ?>", {codigoEstado: $("#TbAssociado_idestado option:selected").val()}, function(data) {
                var options = '<option value=""></option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i].id + '">' + data[i].cidade + '</option>';
                }
                $(".ddlcidade .select2-choice .select2-chosen").html("");
                $('#TbAssociado_idcidade').html(options).show();
                $(".ddlcidade .select2-choice .select2-chosen").html("Selecione");
                var codigoCidade = parseInt('<?= $model->idcidade ?>');
                if (codigoCidade > 0) {
                    $("#TbAssociado_idcidade option[value='<?= $model->idcidade ?>']").attr("selected", true);
                }
            }, "json");
        } else {
            $(".ddlcidade .select2-choice .select2-chosen").html("");
            $('#TbAssociado_idcidade').html('<option value="">-- Escolha um estado --</option>');
            $(".ddlcidade .select2-choice .select2-chosen").html("-- Escolha um estado --");
        }
    }

    $(document).ready(function() {
        //Adicionando novas Tags para telefone
        $("#bntaddFone").click(function() {

            var cont = 0;
            var sair = 0;

            //adicionar próximo componento do conjunto Array
            do {
                if ($("#txtFone_" + cont).length) {
                    cont++;
                } else {
                    sair = 1;
                    break;
                }
            } while (sair === 0);

            $('#itens').append("<div class='row'><section class='col col-2'> " +
                    "<label class='label'>Novo telefone</label>" +
                    "<div class='input'><i class='icon-append fa fa-phone'></i>" +
                    "<input id='txtFone_" + cont + "' class='fone' name='txtFone[" + cont + "]' type='text' onkeyup='mascaraTelefone(\"" + cont + "\")'>" +
                    "</div></section><section class='col col-2'><label class='label'>Operadora</label>" +
                    "<label class='select'> <i class='icon-append fa'></i>" +
                    "<?php $modelOperadora = TbOperadoras::model()->findAll(); ?>" +
                    "<select name='ddlOperadora[" + cont + "]' id='ddlOperadora_" + cont + "' empty='Selecione' class='select2'>" +
                    "<?php foreach ($modelOperadora as $list) { ?>" +
                    "<option value='<?php echo $list->id; ?>'><?php echo $list->operadora; ?></option>" +
                    "<?php } ?>" +
                    "</select></label></section></div>");

            $("#txtFone_" + cont).focus();
        });

        /****** INICIO CONTROLE DOS POP-UPS PARA CADASTRO DA CIDADE ******/

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

        // modal dialog init: custom buttons and a "close" callback reseting the form inside
        var dialogC = $("#addCidade").dialog({
            autoOpen: false,
            width: 600,
            resizable: false,
            modal: true
        });

        // addEstado button: just opens the dialog
        $("#add_cidade").click(function() {
            var estado = $('.ddlestado .select2-choice .select2-chosen').text();

            if (estado != "••• Selecione o estado desejado •••") {

                $("#Cidade_cidade").val("");
                $("#loadingC").hide();
                $("#msgCidadeWarning").hide();
                $("#msgCidadeSucesso").hide();

                dialogC.dialog("open");
                $("#Cidade_cidade").focus();
            } else {
                $.SmartMessageBox({
                    title: "<i class='fa-fw fa fa-info-circle'></i><span class='txt-color-orangeDark'>ATENÇÃO:</span>",
                    content: "Para Cadastra uma nova cidade, é necessário selecionar um estado!",
                    buttons: '[Fechar]'
                });
            }
        });
        /**** FIM ****/

    });
    function mascaraTelefone(cont) {
        $("#txtFone_" + cont).val(Util.mascaraTelefone($("#txtFone_" + cont).val()));
    }
    $(function() {

        $("#TbAssociado_nomerazao").keyup(function() {
            $("#TbAssociado_nomerazao").val($("#TbAssociado_nomerazao").val().toUpperCase());
        });

        $("#TbAssociado_bairro").keyup(function() {
            $("#TbAssociado_bairro").val($("#TbAssociado_bairro").val().toUpperCase());
        });

        $("#TbAssociado_logradouro").keyup(function() {
            $("#TbAssociado_logradouro").val($("#TbAssociado_logradouro").val().toUpperCase());
        });

        $("#TbAssociado_numero").keyup(function() {
            $("#TbAssociado_numero").val($("#TbAssociado_numero").val().toUpperCase());
        });

        $("#TbAssociado_complemento").keyup(function() {
            $("#TbAssociado_complemento").val($("#TbAssociado_complemento").val().toUpperCase());
        });

        // Validation            
        $("#clientepj-form").validate({
            // Rules for form validation
            rules: {
                'TbAssociado[nomerazao]': {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                'TbAssociado[documento]': {
                    required: true
                },
                'TbAssociado[responsavel]': {
                    required: true
                },
                'TbAssociado[emailresponsavel]': {
                    required: true,
                    email: true
                }
            },
            // Messages for form validation
            messages: {
                'TbAssociado[nomerazao]': {
                    required: '<label style="color: red">Digite a razão sócial</label>',
                    minlength: '<label style="color: red">A razão sócial deve ter no mínimo 03 caracteres</label>',
                    maxlength: '<label style="color: red">A razão sócial não pode ultrapassar 255 caracteres</label>'
                },
                'TbAssociado[documento]': {
                    required: '<label style="color: red">Digite o CNPJ</label>'
                },
                'TbAssociado[responsavel]': {
                    required: '<label style="color: red">Digite o nome do responvável</label>',
                    minlength: '<label style="color: red">O responsável deve ter no mínimo 03 caracteres</label>',
                    maxlength: '<label style="color: red">O responsável não pode ultrapassar 255 caracteres</label>'
                },
                'TbAssociado[emailresponsavel]': {
                    required: '<label style="color: red">Informa o e-mail do responvável</label>',
                    email: '<label style="color: red">Informe um e-mail válido do responvável</label>'
                }
            },
            // Do not change code below
            /* errorPlacement: function(error, element) {
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
             },*/
            submitHandler: function(form) {

                $("#btnCadastro").hide();
                $("#btnVolta").hide();
                $("#loading").show();
                $("#msgWarning").hide();

                var razao = $("#TbAssociado_nomerazao").val();
                var documento = $("#TbAssociado_documento").val();
                var estado = $("#TbAssociado_idestado").val();
                var cidade = $("#TbAssociado_idcidade").val();
                var bairro = $("#TbAssociado_bairro").val();
                var cep = $("#TbAssociado_cep").val();
                var logradouro = $("#TbAssociado_logradouro").val();
                var numero = $("#TbAssociado_numero").val();
                var complemento = $("#TbAssociado_complemento").val();
                var email = $("#TbAssociado_email").val();
                var home = $("#TbAssociado_home").val();
                var responsavel = $("#TbAssociado_responsavel").val();
                var emailresponsavel = $("#TbAssociado_emailresponsavel").val();

                //conjunto Array
                var fones = new Array();
                var operadoras = new Array();

                var cont = 0;
                var sair = 0;

                do {
                    if ($("#txtFone_" + cont).length) {
                        fones[cont] = $("#txtFone_" + cont).val();
                        operadoras[cont] = $("#ddlOperadora_" + cont).val();
                        cont++;
                    } else {
                        sair = 1;
                        break;
                    }
                } while (sair === 0);
                //fim do conjunto Array
                var url = "";
                if ("<?= $page; ?>" == 'create') {
                    url = "<?= Yii::app()->createAbsoluteUrl('/main/associados/createpj/') ?>";
                } else {
                    url = document.URL;
                }

                $.post(url, {razao: razao, documento: documento, estado: estado, cidade: cidade, bairro: bairro,
                    cep: cep, logradouro: logradouro, numero: numero, complemento: complemento, email: email,
                    home: home, responsavel: responsavel, emailresponsavel: emailresponsavel, fones: fones, operadoras: operadoras}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        if (typeof data.user == "undefined") {
                            window.location = "<?= Yii::app()->createAbsoluteUrl('/main/associados/index/msg/' . $page) ?>";
                        } else {
                            window.location = "<?= Yii::app()->createAbsoluteUrl('/main/associados/index/msg/' . $page) ?>" + "/user/" + data.user + "/pwd/" + data.pwd;
                        }
                    } else {

                        $("#msg").html(data.msg);
                        $("#msgWarning").show();
                        $("#loading").hide();
                        $("#btnCadastro").show();
                        $("#btnVoltar").show();
                    }

                }, "json");

                return false;
            }
        });

        $("#TbAssociado_cep").mask("99.999-999");
        $("#TbAssociado_documento").mask("99.999.999/9999-99");
    });
</script>
<!-- RIBBON -->
<div id="ribbon">
    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            <?php
            $acao = "";
            if ($page == "create") {
                $acao = "Adicionar novo";
            } else {
                $acao = "Atualizar";
            }
            ?>
            Associados / <?= $acao; ?> - Pessoa Jurídica                             
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
                    <?php
                    $acao = "";
                    if ($page == "create") {
                        $acao = "Adicionar novo";
                    } else {
                        $acao = "Atualizar";
                    }
                    ?>
                    Associados / <?= $acao; ?> - Pessoa Jurídica       
                </h2>				                    
            </header>
            <!-- widget div-->
            <div>
                <!-- content goes here -->                
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'clientepj-form',
                    'htmlOptions' => array('class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                ));
                ?>                  
                <?php if ($form->errorSummary($model)) { ?>
                    <div id="msgWarning" class="alert alert-warning fade in" >
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-warning"></i>
                        <strong>Atenção!</strong><br><br>
                        <span id="msg"></span>
                    </div>                    
                <?php } ?>

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <header >
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header>
                    <fieldset id="itens">
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'nomerazao'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-briefcase"></i>
                                    <?php echo $form->textField($model, 'nomerazao', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label">CNPJ *</label>
                                <label class="input"> <i class="icon-append fa  fa-list-alt"></i>
                                    <?php echo $form->textField($model, 'documento', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label">
                                    <label class="label"><?php echo $form->labelEx($model, 'cep'); ?> <label style="color: green;"><b>(Busca automática)</b></label>
                                    </label>
                                </label>
                                <label class="input">
                                    <i class="icon-append">
                                        <i id="iconLoadBusca" style="display: none;" class="fa fa-spinner fa-spin txt-color-red"></i>
                                        <i id="iconBuscaCep" class=" fa fa-globe fa-spin  txt-color-green" title="Click para fazer a Busca do endereço através do CEP informado!" href="javascript:void(0);" onclick="BuscarCEP();"></i>                                        
                                    </i>                                    
                                    <?php echo $form->textField($model, 'cep', array('size' => 60, 'maxlength' => 255)); ?>                                  

                                </label>
                            </section> 
                        </div>                    
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label">Estado *</label>
                                <div class="input">
                                    <?php
                                    $modelEstado = TbEstado::model()->findAll();
                                    $list = CHtml::listdata($modelEstado, "id", "estado");
                                    ?>                                                            
                                    <?php echo $form->dropDownList($model, 'idestado', $list, array('empty' => '••• Selecione o estado desejado •••', 'onchange' => 'carregarCidades()', 'class' => 'select2 ddlestado')); ?>                                                                                                                                                                     
                                </div>                                
                            </section>    
                            <section class="col col-lg-4">
                                <label class="label">Cidade *</label>
                                <div class="input-group">
                                    <?php
                                    $list = array();
                                    if ($page == "update") {
                                        $criteria = new CDbCriteria;
                                        $criteria->condition = "idestado = :estado";
                                        $criteria->params = array(":estado" => $model->idestado);

                                        $cidade = TbCidade::model()->findAll($criteria);
                                        $list = CHtml::listdata($cidade, "id", "cidade");
                                    }
                                    ?>
                                    <?php echo $form->dropDownList($model, 'idcidade', $list, array('class' => 'select2 ddlcidade'), array('empty' => '')); ?>                                     
                                    <span id="add_cidade" class="input-group-addon bg-color-white" title="Click para cadastrar uma nova cidade!">
                                        <i id="add_cidade" class=" fa fa-plus-circle fa-spin  txt-color-green" title="Click para cadastrar uma nova cidade!"></i>                                       
                                    </span>
                                </div>                               
                            </section>                       
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'bairro'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-home"></i>
                                    <?php echo $form->textField($model, 'bairro', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                        </div> 
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'logradouro'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-home"></i>
                                    <?php echo $form->textField($model, 'logradouro', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'numero'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-home"></i>
                                    <?php echo $form->textField($model, 'numero', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'complemento'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-home"></i>
                                    <?php echo $form->textField($model, 'complemento', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'home'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-globe"></i>
                                    <?php echo $form->textField($model, 'home', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'email'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-envelope"></i>
                                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                        </div> 
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'responsavel'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-globe"></i>
                                    <?php echo $form->textField($model, 'responsavel', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'emailresponsavel'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-envelope"></i>
                                    <?php echo $form->textField($model, 'emailresponsavel', array('size' => 60, 'maxlength' => 100)); ?>                                                                        
                                </label>
                            </section>   
                        </div> 
                        <!-- INÍCIO DO FONE -->
                        <?php
                        if ($page == 'create') {
                            ?>
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">Telefones</label>
                                    <div class="input">
                                        <i class="icon-append fa fa-phone"></i>
                                        <input id="txtFone_0" class="fone" name="txtFone[0]" type="text" onkeyup='mascaraTelefone("0")'>                                    
                                    </div>
                                </section> 
                                <section class="col col-2">
                                    <label class="label">Operadora</label>
                                    <label class="select"> <i class="icon-append fa"></i>
                                        <?php
                                        $modelOperadora = TbOperadoras::model()->findAll();
                                        $list = CHtml::listdata($modelOperadora, "id", "operadora");
                                        ?>
                                        <?php echo CHtml::dropDownList('ddlOperadora[0]', '', $list, array('id' => 'ddlOperadora_0')); ?>
                                    </label>
                                </section>   
                                <section class="col">
                                    &nbsp;
                                    <div class="input" >
                                        <span class="button" nome="bntaddFone" id="bntaddFone" ><i class="fa fa-phone"></i>&nbsp;&nbsp;Adicionar</span>
                                    </div>
                                </section>
                            </div>
                        <?php } ?>
                        <!-- Adicionando Tags Telefones se '$Page == Update' -->
                        <?php
                        if ($page == 'update') {
                            $criteria = new CDbCriteria;
                            $criteria->condition = "idtabela=:idtabela and tabela=:tabela and status=:status";
                            $criteria->params = array(":idtabela" => $model->id, "tabela" => "associados", "status" => "A");

                            $cont = TbTelefones::model()->count($criteria);

                            //Se não houver Ocorrência, incluir o padrão (telefone, operador e buton 'adcionar')
                            if ($cont == 0) {
                                ?>
                                <div class="row">
                                    <section class="col col-2">
                                        <label class="label">Telefones</label>
                                        <div class="input">
                                            <i class="icon-append fa fa-phone"></i>
                                            <input id="txtFone_0" class="fone" name="txtFone[0]" type="text" onkeyup='mascaraTelefone("0")'>                                    
                                        </div>
                                    </section> 
                                    <section class="col col-2">
                                        <label class="label">Operadora</label>
                                        <label class="select"> <i class="icon-append fa"></i>
                                            <?php
                                            $modelOperadora = TbOperadoras::model()->findAll();
                                            $list = CHtml::listdata($modelOperadora, "id", "operadora");
                                            ?>
                                            <?php echo CHtml::dropDownList('ddlOperadora[0]', '', $list, array('id' => 'ddlOperadora_0')); ?>
                                        </label>
                                    </section>   
                                    <section class="col">
                                        &nbsp;
                                        <div class="input" >
                                            <span class="button" nome="bntaddFone" id="bntaddFone" ><i class="fa fa-phone"></i>&nbsp;&nbsp;Adicionar</span>
                                        </div>
                                    </section>
                                </div>
                                <?php
                            } else {
                                //se hover ocorrência Adciona Campos referênte a quantidade das ocorrências
                                $cont = 0;
                                $fones = TbTelefones::model()->findAll($criteria);
                                foreach ($fones as $fn) {
                                    ?>
                                    <div class="row">
                                        <section class="col col-2">
                                            <label class="label">Telefones</label>
                                            <div class="input">
                                                <i class="icon-append fa fa-phone"></i>
                                                <input id="txtFone_<?php echo $cont; ?>" name="txtFone[<?php echo $cont; ?>]" type="text" value="<?php echo $fn->numero; ?>"  onkeyup='mascaraTelefone("<?php echo $cont ?>")'>                                    
                                            </div>
                                        </section> 
                                        <section class="col col-2">
                                            <label class="label">Operadora</label>
                                            <label class="select"> <i class="icon-append fa"></i>
                                                <?php
                                                $modelOperadora = TbOperadoras::model()->findAll();
                                                $list = CHtml::listdata($modelOperadora, "id", "operadora");
                                                ?>
                                                <?php echo CHtml::dropDownList('ddlOperadora[' . $cont . ']', '', $list, array('id' => 'ddlOperadora_' . $cont, 'options' => array($fn->operadora => array('selected' => true)))); ?>
                                            </label>
                                        </section>  
                                        <?php if ($cont == 0) { ?>
                                            <section class="col">
                                                &nbsp;
                                                <div class="input" >
                                                    <span class="button" nome="bntaddFone" id="bntaddFone" ><i class="fa fa-phone"></i>&nbsp;&nbsp;Adicionar</span>
                                                </div>
                                            </section>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    $cont++;
                                }
                            }
                        }
                        ?>
                        <!-- Fim da Adição de Tags telefones -->
                    </fieldset>
                    <footer>                        
                        <button id="btnCadastro" type="submit" class="btn btn-primary">
                            <i class=" fa fa-check"></i>
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
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/associados/index';
    }
    function BuscarCEP() {
        $("#msgWarning").hide();
        $("#iconBuscaCep").hide();
        $("#iconLoadBusca").show();

        var msgPesquisaCep = $('#msgPesquisaCep');
        msgPesquisaCep.dialog({
            modal: true
        });


        $(".ui-dialog-titlebar-close").hide();

        cep = $("#TbAssociado_cep").val();

        if (cep.length > 0) {
            cep = Util.limparCaracteres(cep);
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/buscarcep/') ?>", {cep: cep}, function(data) {

                if (data.resultado == 1) {

                    $("#TbAssociado_idestado option[value='" + data.iduf + "']").attr("selected", true);
                    $(".ddlestado .select2-choice .select2-chosen").html(data.uf);

                    $("#TbAssociado_idcidade").html("");
                    $("#TbAssociado_idcidade").append("<option value='" + data.idcidade + "'>" + data.cidade + "</option>").attr("selected", true);
                    $(".ddlcidade .select2-choice .select2-chosen").html(data.cidade);

                    $("#TbAssociado_bairro").val(data.bairro);
                    $("#TbAssociado_logradouro").val(data.tipo_logradouro + " " + data.logradouro);

                    $("#msgPesquisaCep").dialog('close');
                    $("#iconLoadBusca").hide();
                    $("#iconBuscaCep").show();
                    $("#TbAssociado_numero").focus();

                } else {
                    $("#msgPesquisaCep").dialog('close');
                    $("#msg").html(data.resultado_txt);
                    $("#msgWarning").show();
                    $("#iconLoadBusca").hide();
                    $("#iconBuscaCep").show();
                }
            }, "json");
        } else {
            $("#msgPesquisaCep").dialog('close');
            $("#iconLoadBusca").hide();
            $("#iconBuscaCep").show();
        }

    }
</script>
<div id="msgPesquisaCep" style="display: none" title="&nbsp; &nbsp; &nbsp; Pesquisando, Aguarde...">
    <center><img style="width: 50%;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/loading-icon.gif" ></center>    
</div>
<!-- Pop-Up Adicionar Nova Cidade -->
<?php $this->renderPartial('addCidade', array()); ?>
<!-- -->