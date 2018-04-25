<script type="text/javascript">

    $(function() {

        $("#TbDepartamento_departamento").keyup(function() {
            $("#TbDepartamento_departamento").val($("#TbDepartamento_departamento").val().toUpperCase());
        });

        // Validation            
        $("#departamento-form").validate({
            // Rules for form validation
            rules: {
                'TbDepartamento[departamento]': {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                }
            },
            // Messages for form validation
            messages: {
                'TbDepartamento[departamento]': {
                    required: 'Digite o departamento',
                    minlength: 'O departamento deve ter no mínimo 03 caracteres',
                    maxlength: 'O departamento não pode ultrapassar 30 caracteres'
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
                $("#btnNovo").hide();
                $("#btnVoltar").hide();
                $("#loading").show();

                $("#msgSuccess").hide();
                $("#msgWarning").hide();

                var departamento = $("#TbDepartamento_departamento").val();

                var url = "";

                <?php if ($page == 'create') { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/departamento/create') ?>";
                <?php } else { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/departamento/update/id/' . $_GET['id']); ?>";
                <?php } ?>

                $.post(url, {departamento: departamento}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/departamento/index/msg/' . $page) ?>";
                    } else {

                        $("#msgW").html(data.msg);

                        $("#loading").hide();
                        $("#btnNovo").show();
                        $("#msgWarning").show();
                        $("#btnCadastro").show();
                        $("#btnVoltar").show();
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
            <?php
            $acao = "";
            if ($page == "create") {
                $acao = "Departamento / Adicionar novo";
            } else {
                $acao = "Departamento / Atualizar";
            }
            ?>    
            <?= $acao; ?>
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
                    <h2>Adicionar Departamento</h2>				                    
                <?php } else { ?>
                    <h2>Atualizar Departamento</h2>				                    
                <?php } ?>

            </header>           
            <!-- widget div-->
            <div>                  
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'departamento-form',
                    'htmlOptions' => array('class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                ));
                ?>
                <?php
                if ($_GET) {
                    if (isset($_GET['msg']) && empty($_GET['msg'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/departamento/index'));
                    } elseif (isset($_GET['id']) && empty($_GET['id'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/departamento/index'));
                    } elseif (!isset($_GET['msg']) && !isset($_GET['id'])) {
                        $this->redirect(Yii::app()->createAbsoluteUrl('main/departamento/index'));
                    }
                }
                ?>                
                <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-warning"></i>
                    <strong>Atenção</strong><br><br>
                    <span id="msgW"></span>
                </div>
                <div class="widget-body no-padding">
                    <header>
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header> 
                    <fieldset>
                        <div class="row">
                            <section class="col col-8">
                                <label class="label"><?php echo $form->labelEx($model, 'departamento'); ?></label>
                                <label class="input"> <i class="icon-append fa fa-archive"></i>
                                    <?php echo $form->textField($model, 'departamento', array('size' => 60, 'maxlength' => 255)); ?>
                                </label>
                            </section>
                        </div>
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
                        <button id="btnVoltar" class="btn btn-default" onclick="voltar();" type="button"><i class=" fa fa-reply"></i>&nbsp; Voltar</button>
                    </footer>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </section>
</div>
<!-- END MAIN CONTENT -->
<script>
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/departamento/index';
    }
</script>