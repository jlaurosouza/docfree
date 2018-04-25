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
                    iconSmall: "icon-repeat fadeInRight animated",
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
                        });
                    } else {
                        $.SmartMessageBox({
                            title: "<i class='fa fa-warning'></i><span class='txt-color-orangeDark'> Falha no Processo!</span>",
                            content: data.msg,
                            buttons: '[Ok]'
                        });
                    }
                }, "json");
            }
        });
    }
    /* == Função Selecionar Documento == */
    $(function() {

        // Validation	
        $('#logomarca-form').validate({
            // Rules for form validation
            rules: {
                'inputArquivo': {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                'inputArquivo': {
                    required: 'Selecione uma imagem'
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
              
                }
            }
        });

        /* == Testar se Existe Documento Selecionado e se é Válido  == */
        var arquivo = document.getElementById("file");

        arquivo.addEventListener("change", function(event) {
            var nome = arquivo.files[0].name;

            var Extensao = nome.substring(nome.lastIndexOf('.') + 1);

            var extensoes_permitidas = new Array("PNG", "JPG", "JPEG");

            if (!Util.compararExtensao(Extensao, extensoes_permitidas)) {
                $("#logoM").hide();
                $.SmartMessageBox({
                    title: "<i class='fa fa-exclamation-triangle txt-color-orangeDark'></i><span > Selecione uma imagem válido!</span>",
                    content: "A imagem selecionada não é do tipo suportado.",
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
    });
    /* == Fim da Função Selecionar Documento */

    /* == função Minuatura de Imagem */
    function readURL(input) {
        if (!$(".divMessageBox").is(":visible")) {
            var fileCont = document.getElementsByClassName("fileLogo")[0];
            if (fileCont) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    //alert(e.data);
                    var lab = document.getElementsByClassName("lblLogo")[0];
                    //var lab = fileCont.files[0];
                    $('#imgLogo').remove();
                    $img = $('<img id="imgLogo" style="width:40%;"/>').attr('src', e.target.result);
                    $("#divImagem").after($img);
                    lab.innerHTML = "Pre-visualização da Logomarca, <strong class='txt-color-red'>APLIQUE A IMAGEM PARA VÁLIDAR</strong>";
                    $("#logoM").show();
                },
                        reader.readAsDataURL(input.files[0]);
            }
        }
    }

    function verificaMostraBotao() {
        $('#divImagem').each(function(index) {
            if ($('#divImagem').eq(index).val() != "") {
                $('.hide').show();
            }
        });
    }

    $('body').on("change", "input[type=file]", function() {
        verificaMostraBotao();
        readURL(this);
    });

    $('.hide').on("click", function() {
        $('#divImagem').append(verificaMostraBotao);
        $('.hide').hide();
    });
    /* == fim função Minuatura de Imagem */

</script>
<!-- RIBBON -->
<div id="ribbon">

    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            Logomarca Associado  
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>

<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-5">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-picture-o"></i>
                Assossiados
                <span>> Logomarca </span>
            </h1>
        </div>        
    </div>
    <section id="widget-grid"  >   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <?php
                $nomeRazao = "";

                //$modelCli = TbAssociado::model()->findByPk($_GET['ce']);

                if ($model->tipo == 'F') {
                    $nomeRazao = $model->nomefantasia;
                } else {
                    $nomeRazao = $model->nomerazao;
                }
                //$nomeRazao = "{Nome da Empresa Associada}";
                ?> 
                <?php if (empty($model->logomarca)) { ?>
                    <h2><i class=" cus-application-edit"></i> Adicionar Logomarca de: <strong style="color: #003bb3"> <?php echo $nomeRazao; ?> </strong> </h2>				                    
                <?php } else { ?>
                    <h2><i class=" cus-application-edit"></i> Atualizar Logomarca de: <strong style="color: #003bb3"> <?php echo $nomeRazao; ?> </strong> </h2>				                    
                <?php } ?>  

            </header>

            <div>
                <!-- content goes here -->
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'logomarca-form',
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
                        <?php echo $form->errorSummary($model, ""); ?>
                    </div>                    
                <?php } ?>           
                <div class="widget-body no-padding">
                    <header >
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header>
                    <fieldset>
                        <div class="row">                            
                            <section class="col col-lg-12">
                                <label class="label" for="inputArquivo">Logomarca *</label>
                                <div class="input input-file ">
                                    <span class="button"><?php echo CHtml::fileField('file', '', array('id' => 'file', 'onchange' => "this.parentNode.nextSibling.value = this.value", "class" => "fileLogo")); ?>Selecione uma imagem</span><input id="inputArquivo" name="inputArquivo" type="text" readonly>
                                </div>
                                <div class="note">Tipos suportados: PNG, JPG, JPEG.</div>
                            </section>  
                        </div>

                        <!-- DIV DA LOGO SELECIONADA -->
                        <?php if (!empty($model->logomarca)) { ?>
                            <div id="logoM" class="row logomarca">
                                <section class="col col-lg-12">
                                    &nbsp;
                                    <div id="divImagem" class="lblLogo">Logomarca</div>
                                    <img id="imgLogo" style="width:40%;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logomarca/uploads/associados/<?php echo $model->logomarca; ?>">                                                
                                </section>                                            
                            </div>
                        <?php } else { ?>
                            <div id="logoM" style="display: none" class="row logomarca">
                                <section class="col col-lg-12"">
                                    &nbsp;
                                    <div id="divImagem" class="lblLogo">Logomarca</div>
                                    <img id="imgLogo" style="width:40%;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logomarca/uploads/associados/<?php echo $model->logomarca; ?>">                                            
                                </section>                                            
                            </div>                                    
                        <?php }; ?>
                    </fieldset>
                    <footer>                            
                        <button id="btnCadastro" type="submit" class="btn medium btn-primary"><i class="fa fa-check"></i> Aplicar Imagem</button>
                        <?php if (!empty($model->logomarca)) { ?>
                            <button id="btnRemover" class="btn btn-default" onclick="removerLogo(<?php echo $model->id; ?>);" type="button"><i class="fa fa-trash-o txt-color-red"></i> Remover Logomarca</button>
                        <?php }; ?> 
                        <img style="display: none" id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/img/ajax-loader.gif" >
                        <button id="bntVoltar" class="btn medium btn-default" onclick="voltar();" type="button"><i class="fa fa-reply"></i>&nbsp; Voltar</button>
                    </footer>

                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </section>
</div>
<script>
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/associados/index';
    }
</script>