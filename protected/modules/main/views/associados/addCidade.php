<script type="text/javascript">
    $(document).ready(function() {

        function recarregarCidade() {
       
            $("#TbAssociado_idcidade").html("");
            $(".ddlcidade .select2-choice .select2-chosen").html("carregando...");
            var idestado = $("#Cidade_estado").val();
            $.getJSON("<?= Yii::app()->createAbsoluteUrl('/main/default/getlistacidade/') ?>", {idestado: idestado, ajax: 'true'}, function(j) {
                var options = '<option value=""></option>';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].id + '">' + j[i].cidade + '</option>';
                }
                $(".ddlcidade .select2-choice .select2-chosen").html("");
                $('#TbAssociado_idcidade').html(options).show();
                $(".ddlcidade .select2-choice .select2-chosen").html("Selecione");
            });
        }

        // Validando Fromulário do Pop-Up cidade
        $('#cidade-form').validate({
            // Rules for form validation
            rules: {
                'Descricao[estado]': {
                    required: true
                },
                'Cidade[cidade]': {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                }
            },
            // Messages for form validation
            messages: {
                'Descricao[estado]': {
                    required: 'Favor selecione o estado'
                },
                'Cidade[cidade]': {
                    required: 'Digite a Cidade',
                    minlength: 'A cidade deve conter no mínimo 03 caracteres',
                    maxlength: 'A cidade deve conter no máximo 50 caracteres'
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
                }
            },
            submitHandler: function(form) {

                $("#btnCadastroCidade").hide();
                $("#loadingC").show();
                $("#msgCidadeWarning").hide();
                $("#msgCidadeSucesso").hide();

                var estado = $("#Cidade_estado").val();
                var cidade = $("#Cidade_cidade").val();

                var url = "<?= Yii::app()->createAbsoluteUrl('/main/default/createcidade'); ?>";

                $.post(url, {estado: estado, cidade: cidade}, function(data) {

                    if (data.tipo == "SUCESSO") {

                        recarregarCidade();

                        $("#loadingC").hide();
                        $("#btnCadastroCidade").show();
                        $("#msgCidadeSucesso").show();

                        $("#Cidade_estado").val("");
                        $("#Cidade_cidade").val("");

                    } else {
                        $("#msgCW").html(data.msg);
                        $("#loadingC").hide();
                        $("#btnCadastroCidade").show();
                        $("#msgCidadeWarning").show();
                    }

                }, "json");
                return false;
            }
        });
    });

</script>
<div id="addCidade" title="<div class='widget-header'><h4><i class='fa fa-pencil-square-o'></i> Cadastrar Nova Cidade</h4></div>">
    <form id="cidade-form" class="smart-form" method="post"> <!-- novalidate="novalidate" -->       
        <div id="msgCidadeWarning" class="alert alert-warning fade in" style="display: none">
            <button class="close" data-dismiss="alert">×</button>
            <i class="fa-fw fa fa-warning"></i>
            <strong>Atenção!</strong><br><br>
            <span id="msgCW"></span>
        </div>
        <div id="msgCidadeSucesso" class="alert alert-success fade in" style="display: none">
            <button class="close" data-dismiss="alert">×</button>
            <i class="fa-fw fa fa-check"></i>
            <strong>Sucesso!</strong><br><br>
            <span>Cidade Cadastrada com Sucesso!</span>
        </div>
        <header >
            <label class="text-danger">Os campos com * são obrigatórios.</label>
        </header>      
        <fieldset>
            <div class="row">
                <section class="col col-6">
                    <label>Estado Selecionado</label>
                    <label class="input">
                        <input class="form-control" id="Cidade_estado" name="Cidade[estado]" type="hidden" >                
                        <input class="form-control" id="Descricao_estado" name="Descricao[estado]" type="text" readonly="true">                                       
                    </label>
                </section>
                <section class="col col-lg-6">
                    <label>Cidade *</label>
                    <label class="input">
                        <input class="form-control" id="Cidade_cidade" name="Cidade[cidade]" type="text" >  
                    </label>
                </section>
            </div>
        </fieldset>
        <hr>
        <img id="loadingC" style="display: none; margin-top: 7px!important; float: right!important;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" >            
        <button id="btnCadastroCidade" class="btn btn-primary bnt-not-footer" style="margin-top: 3px!important;" type="submit">
            <i class="fa fa-check"></i>
            Cadastrar
        </button> 
        <br><br>
        <hr>
        <br>          
    </form>
</div>
