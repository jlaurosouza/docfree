<script type="text/javascript">

    $(function() {
        // Validation            
        $("#login-form").validate({
            // Rules for form validation
            rules: {
                'txtusuario': {
                    required: true,
                    minlength: 2
                },
                'txtsenha': {
                    required: true,
                    minlength: 6
                }
            },
            // Messages for form validation
            messages: {
                'txtusuario': {
                    required: 'Digite seu usuário.',
                    minlength: 'Usuário tem que ter no mínimo 2 caracteres.'
                },
                'txtsenha': {
                    required: 'Digite sua senha',
                    minlength: 'A senha precisa possuir no mínimo 6 digitos'
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

                $("#btnEntrar").hide();
                $("#loading").show();

                $("#msgativa").hide();
                $("#msgSuccess").hide();
                $("#msgWarning").hide();

                var usuario = $("#txtusuario").val();
                var senha = $("#txtsenha").val();

                var lembrar = '0';
                if ($("#remember").is(':checked')) {
                    lembrar = '1';
                }
                
                $.post("<?= Yii::app()->createAbsoluteUrl('/login/default/logar/') ?>", {usuario: usuario, senha: senha, lembrar: lembrar}, function(data) {
                    if (data == 1) {
                        $("#loading").hide();
                        $("#msgSuccess").show();

                        window.location = "<?= Yii::app()->createAbsoluteUrl('/main/default/index/') ?>";

                    } else {

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
<center><img style="width: 200px;height: auto;padding-left: 0px; color: black!important;" class="imgContraste" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo/logo.png" alt="MacedoRocha"></center>
<br>
<div class="well no-padding">    
    <form id="login-form" class="smart-form client-form">        
        <header>
            Acessar sistema
        </header>

        <fieldset>
            <?php
            if (($_GET) && ($_GET['a'] == "ok")) {
                ?>
                <div id="msgativa" class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-check"></i>
                    <strong>Sucesso</strong> Conta Ativa!
                </div>
            <?php } ?>

            <div id="msgSuccess" class="alert alert-success fade in" style="display: none">
                <button class="close" data-dismiss="alert">×</button>
                <i class="fa-fw fa fa-check"></i>
                <strong>Sucesso</strong> redirecionando...
            </div>

            <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                <button class="close" data-dismiss="alert">×</button>
                <i class="fa-fw fa fa-warning"></i>
                <strong>Atenção</strong> Usuário ou Senha incorreto.
            </div>

            <section>
                <label class="label">Usuário</label>
                <label class="input"> <i class="icon-append fa fa-user"></i>
                    <input type="input" name="txtusuario" id="txtusuario">
                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Insira seu usuário</b></label>
            </section>

            <section>
                <label class="label">Senha</label>
                <label class="input"> <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="txtsenha" id="txtsenha">
                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Insira sua senha</b> </label>
<!--                <div class="note">
                    <a href="<-?= Yii::app()->createAbsoluteUrl('/login/default/recuperarsenha/') ?>">Esqueceu sua senha?</a>
                </div>-->
            </section>

            <section>
                <label class="checkbox">
                    <input type="checkbox" name="remember" id="remember" checked="">
                    <i></i>Mantenha-me conectado</label>
            </section>
        </fieldset>
        <footer>
            <button id="btnEntrar" type="submit" class="btn btn-primary" tabindex="0">
                Entrar
            </button>
            <img id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif">            
        </footer>
    </form>

</div>
<script>
    $("#txtsenha").keypress(function(e) {
        if (e.which == 13) {
            $('#btnEntrar').click();
        }
    });
</script>