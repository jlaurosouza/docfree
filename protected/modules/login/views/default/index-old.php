<script type="text/javascript">


    $(function() {

        // Validation            
        $("#login-form").validate({
            // Rules for form validation
            rules: {
                'txtusuario': {
                    required: true
                },
                'txtsenha': {
                    required: true,
                    minlength: 3
                }
            },
            // Messages for form validation
            messages: {
                'txtusuario': {
                    required: '<label style="color: red">Digite seu usuário</label>'
                },
                'txtsenha': {
                    required: '<label style="color: red">Digite sua senha</label>',
                    minlength: '<label style="color: red">A senha possuir no mínimo 3 digitos</label>'
                }
            },
//            // Do not change code below
//            errorPlacement: function(error, element) {
//                error.insertAfter(element.parent());
//            },
//            // Check if has error
//            invalidHandler: function(event, validator) {
//                var errors = validator.numberOfInvalids();
//                if (errors)
//                {
//                    //$('#btn').attr('disabled', false).css('cursor', 'pointer');
//                    //$('#loader-button').hide();
//                }
//            },
            submitHandler: function(form) {

                if (!$("#msgWarning").length) {
                    $('#itens').append("<div id='msgWarning' class='alert adjusted alert-warning' style='display: none'>" +
                            "<button class='close' data-dismiss='alert'>×</button>" +
                            "<i class='cus-exclamation-octagon-fram'></i>" +
                            "<strong>Atenção!</strong><p><p> &nbsp;&nbsp;&nbsp; Usuário ou Senha incorreto." +
                            "</div>");
                }

                $("#loading").show();
                $("#btnEntrar").hide();

                $("#msgativa").hide();
                $("#msgSuccess").hide();
                $("#msgWarning").hide();

                var usuario = $("#txtusuario").val();
                var senha = $("#txtsenha").val();

                var lembrar = '0';
                if ($("#remember").is(':checked')) {
                    lembrar = '1';
                }
                
                $.post("<?php echo Yii::app()->createAbsoluteUrl('/login/default/logar/'); ?>", {usuario: usuario, senha: senha, lembrar: lembrar}, function(data) {

                    if (data == 1) {
                        $("#loading").hide();
                        $("#msgSuccess").show();

                        window.location = "<?php echo Yii::app()->createAbsoluteUrl('/main/default/index/') ?>";

                    } else {
                        //alert(data);
                        $("#loading").hide();
                        $("#btnEntrar").show();
                        $("#msgWarning").show();
                    }

                });

                return false;
            }
        });
    });
</script>
<div class="height-wrapper">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'htmlOptions' => array('class' => 'form-signin'),
        'enableAjaxValidation' => false,
    ));
    ?>  
    <div class="control-group">
        <?php
        if (($_GET) && ($_GET['a'] == "ok")) {
            ?>
            <div id="msgativa" class="alert adjusted alert-success">
                <button class="close" data-dismiss="alert">
                    ×
                </button>
                <i class="cus-accept"></i>
                <strong>Sucesso</strong> Conta Ativa!
            </div>
        <?php } ?>
        <div id='itens'> 
            <div id="msgSuccess" class="alert adjusted alert-success" style="display: none">
                <i class="cus-accept"></i>
                <strong>Login Autenticado</strong> redirecionando...
            </div>                          
            <div id="msgWarning" class="alert adjusted alert-warning" style="display: none">
                <button class="close" data-dismiss="alert">×</button>
                <i class="cus-exclamation-octagon-fram"></i>
                <strong>Atenção!</strong><p><p> &nbsp;&nbsp;&nbsp; Usuário ou Senha incorreto.
            </div>            
        </div>
        <label class="control-label">Usuário</label>
        <div class="controls">
            <input name="txtusuario" tabindex="1" id="txtusuario" type="text" class="span12" placeholder="Insira seu usuário" ><i class="field-icon icon-user"></i>
        </div>
        &nbsp
        <div class="controls">
            <label class="control-label">Senha</label>
            <div class="controls">
                <input name="txtsenha" tabindex="2" id="txtsenha" type="password" class="input-block-level" placeholder="Insira sua Senha" /><i class="field-icon icon-lock "></i>
            </div>
        </div>
        <a style="color: blue" href="<-?= Yii::app()->createAbsoluteUrl('/login/default/recuperarsenha/') ?>">Esqueceu sua senha?</a>

    </div>
    <div class="control-group no-border">
        <label class="checkbox inline">
            <div id="uniform-agree" class="checker">
                <span class="checked">
                    <input type="checkbox" name="remember" id="remember" checked="">
                </span>
            </div>
            Mantenha-me conectado
        </label>
        <div style="text-align: right;">
            <input tabindex="3" class="btn medium btn-primary" type="submit" value="Entrar" id="btnEntrar" />
            <img  style="display: none" id="loading" src='<?php echo Yii::app()->request->baseUrl; ?>/assets/img/ajax-loader.gif' >
        </div>
    </div>
    <?php $this->endWidget(); ?> 
</div>
<script>
    $("#txtsenha").keypress(function(e) {
        if (e.which == 13) {
            $('#btnEntrar').click();
        }
    });
</script>