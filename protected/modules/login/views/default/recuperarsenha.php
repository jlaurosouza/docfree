<script type="text/javascript">

    $(function () {
        // Validation            
        $("#recuperar-senha-form").validate({
            // Rules for form validation
            rules: {
                'txtemail': {
                    required: true,
                    email: true
                }
            },
            // Messages for form validation
            messages: {
                'txtemail': {
                    required: 'Digite seu e-mail',
                    email: 'Digite um e-mail válido'
                }
            },
            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            // Check if has error
            invalidHandler: function (event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors)
                {
                    //$('#btn').attr('disabled', false).css('cursor', 'pointer');
                    //$('#loader-button').hide();
                }
            },
            submitHandler: function (form) {

                $("#btnLogar").hide();
                $("#btnEnviar").hide();
                $("#loading").show();
                
                $("#msgSuccess").hide();
                $("#msgWarning").hide();
                $("#msgInfo").hide();

                var email = $.trim($("#txtemail").val());

                $.post("<?= Yii::app()->createAbsoluteUrl('/login/default/recuperarsenhaexe/') ?>", {email: email}, function (data) {

                    $("#loading").hide();
                    $("#btnLogar").show();
                    $("#btnEnviar").show();
                    
                    if(data.tipo == "sucesso"){
                        $("#msgSuccess").show();
                        $("#msgInfo").show();
                    }else{                        
                        $("#msgWarning").show();
                    }
                    
                }, "json");

                return false;
            }
        });
    });
</script>
<div class="well no-padding">
    <form id="recuperar-senha-form" class="smart-form client-form">
        <header>
            Recuperar Senha
        </header>

        <fieldset>

            <div id="msgSuccess" class="alert alert-success fade in" style="display: none">
                <button class="close" data-dismiss="alert">
                    ×
                </button>
                <i class="fa-fw fa fa-check"></i>
                <strong>Parabéns</strong> E-mail enviado com sucesso.
            </div>

            <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                <button class="close" data-dismiss="alert">
                    ×
                </button>
                <i class="fa-fw fa fa-warning"></i>
                <strong>Atenção</strong> Falha ao tentar enviar e-mail.
            </div>
            <div id="msgInfo" class="alert alert-info fade in" style="display: none">
                <button class="close" data-dismiss="alert"> × </button>
                <i class="fa-fw fa fa-info"></i>
                <strong>Como recuperar?</strong><br><br>
                <strong></strong>Para Recuperação de sua conta e senha, você deve acesar seu e-mail e clicar no Link que foi enviando.<br><br>
            </div>
            <section>
                <label class="label">E-mail</label>
                <label class="input"> <i class="icon-append fa fa-envelope-o"></i>
                    <input type="input" name="txtemail" id="txtemail">
                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Insira seu e-mail</b></label>
            </section>

        </fieldset>
        <footer>
            <button id="btnEnviar" type="submit" class="btn btn-primary">
                Enviar
            </button>
            <button id="btnLogar" class="btn btn-default" onclick="voltar();" type="button">Voltar</button>
            <img id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif">
        </footer>
    </form>
</div>
<script>
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/login/default/index/';
    }
</script>