<script type="text/javascript">

    $(document).ready(function() {
        
        $("#TbUsuario_nome").keyup(function() {
            $("#TbUsuario_nome").val($("#TbUsuario_nome").val().toUpperCase());
        });

        // Validation            
        $("#usuarios-form").validate({
            // Rules for form validation
            rules: {
                'TbUsuario[nome]': {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                'TbUsuario[email]': {
                    required: true,
                    email: true
                },
                'TbUsuario[idnivel]': {
                    required: true,
                    minlength: 1,
                    maxlength: 255
                },            
                'TbUsuario[usuario]': {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                'TbUsuario[senha]': {
                    required: true,
                    minlength: 6,
                    maxlength: 255
                }
            },
            // Messages for form validation
            messages: {
                'TbUsuario[nome]': {
                    required: 'Digite o nome',
                    minlength: 'O nome deve ter no mínimo 03 caracteres',
                    maxlength: 'O nome não pode ultrapassar 255 caracteres'
                },
                'TbUsuario[email]': {
                    required: 'Digite o e-mail',
                    email: 'Informe um e-mail válido'
                },
                'TbUsuario[idnivel]': {
                    required: 'Escolha o nível de acesso',
                    SelectName: {valueNotEquals: ""}
                },
                'TbUsuario[usuario]': {
                    required: 'Digite o usuário',
                    minlength: 'O usuário deve ter no mínimo 03 caracteres',
                    maxlength: 'O usuário não pode ultrapassar 255 caracteres'
                },
                'TbUsuario[senha]': {
                    required: 'Digite a Senha',
                    minlength: 'Sua senha deve ter no mínimo 06 caracteres',
                    maxlength: 'Sua senha não pode ultrapassar 10 caracteres'
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
                $("#btnVolta").hide();
                $("#loading").show();

                $("#msgWarning").hide();

                var usuario = $("#TbUsuario_usuario").val();
                var senha = $("#TbUsuario_senha").val();
                var email = $("#TbUsuario_email").val();
                var nome = $("#TbUsuario_nome").val();
                var nivel = $("#TbUsuario_idnivel").val();
                
                //alert(nivel);
                var url = "";
                if ("<?= $page; ?>" == 'create') {
                    url = "<?= Yii::app()->createAbsoluteUrl('/main/usuarios/create/') ?>";
                } else {
                    url = document.URL;
                }

                $.post(url, {usuario: usuario, senha: senha, email: email, nome: nome, nivel: nivel}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/usuarios/index/acao/' . $page) ?>";
                    } else {

                        $("#msg").html(data.msg);

                        $("#msgWarning").show();
                        $("#loading").hide();
                        $("#btnCadastro").show();
                        $("#btnVolta").show();
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
                Usuário / Adicionar Novo            
            <?php } else { ?>
                Usuário / Atualizar
            <?php } ?>
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<!-- END RIBBON -->
<!-- MAIN CONTENT -->
<div id="content" >
    <section id="widget-grid"  >                                        

        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <?php if ($page == 'create') { ?>
                    <h2>Adicionar Usuário</h2>				                    
                <?php } else { ?>
                    <h2>Atualizar Usuário</h2>				                    
                <?php } ?>

            </header>           
            <!-- widget div-->
            <div>                  
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'usuarios-form',
                    'htmlOptions' => array('class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                ));
                ?>
                <?php
                if (isset($msg) && !empty($msg)) {
                    ?>
                    <div id="msgSuccess" class="alert alert-success fade in">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa-fw fa fa-check"></i>            
                        <?php echo $msg; ?>
                    </div>
                <?php } ?>
                <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-warning"></i>
                    <strong>Atenção</strong><br><br>
                    <span id="msg"></span>
                </div>

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <header >
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header> 
                    <fieldset>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'nome'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-user"></i>
                                    <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 255)); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'email'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-envelope"></i>
                                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
                                </label>
                            </section>
                            <section id="secnivelacesso" class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'idnivel'); ?></label>                                
                                <div class="input"> 
                                    <?php
                                    $criteria = new CDbCriteria();
                                    if (Yii::app()->user->idnivel == "1") {
                                        $criteria->order = "id DESC";
                                        $modelNivel = TbNivel::model()->findAll($criteria);
                                    } else {
                                        $criteria->condition = "id<>:id";
                                        $criteria->params = array(":id" => "1");

                                        $modelNivel = TbNivel::model()->findAll($criteria);
                                    }
                                    $list = CHtml::listdata($modelNivel, "id", "nivel");
                                    ?>
                                    <?php echo $form->dropDownList($model, 'idnivel', $list, array('empty' => 'Selecione', 'class' => 'select2 ddlnivel')); ?>                                   
                                </div>                                
                            </section> 
                        </div>
                        <?php
                        if ($page == "create") {
                            ?>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label"><?php echo $form->labelEx($model, 'usuario'); ?></label>
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <?php echo $form->textField($model, 'usuario', array('size' => 60, 'maxlength' => 255)); ?>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label"><?php echo $form->labelEx($model, 'senha'); ?></label>
                                    <label class="input"> <i class="icon-append fa  fa-lock"></i>
                                        <?php echo $form->passwordField($model, 'senha', array('size' => 60, 'maxlength' => 255)); ?>
                                    </label>
                                </section>
                            </div>
                            <?php
                        }
                        ?>                            
                    </fieldset>
                    <footer>         
                        <button id="btnCadastro" type="submit" class="btn btn-primary">
                            <?php
                            if ($page == "create") {
                                ?>
                                <i class=" fa fa-check"></i>&nbsp; Cadastrar
                                <?php
                            } else {
                                ?>
                                <i class=" fa fa-refresh"></i>&nbsp; Atualizar
                                <?php
                            }
                            ?>
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
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/usuarios/index/';
    }
</script>