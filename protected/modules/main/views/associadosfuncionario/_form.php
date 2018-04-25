<script type="text/javascript">

    function oFuncionariosCallback(json) {

        if (!$('#main #oFuncionariosoCallback').length)
        {
            $('#main').append('<div id="oFuncionariosoCallback"></div>');
        }

        $('#oFuncionariosoCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oFuncionarios
         */
        var oFuncionarios = $('#oFuncionarios').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {
                "sSearch": "Pesquisar todas as colunas:",
                "sEmptyTable": "Não há Documentos relacionados a pesquisa",
                "sInfo": "",
                "sInfoEmpty": "",
                "sInfoFiltered": "",
                "sInfoPostFix": "",
                "sLengthMenu": "_MENU_",
                "sLoadingRecords": "Aguarde...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum resultado.",
                "oPaginate": {
                    "sFirst": "Primeira",
                    "sPrevious": "Anterior",
                    "sNext": "Próxima",
                    "sLast": "Última"
                },
            },
            "bSortCellsTop": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/associadosfuncionario/gridfuncionarios/ce') . '/' . $_GET['ce']; ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 30,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oFuncionarios', 4);
                        $('.customPage.oFuncionarios').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oFuncionarios').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oFuncionarios').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oFuncionarios').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oFuncionarios').fadeIn(500);
                    }

                    fnCallback(data);
                    oFuncionariosCallback(data);
                }, 'json');
            }

        });

        var oFuncionariosClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oFuncionarios thead input").keyup(function() {

            var that = this;
            clearTimeout(oFuncionariosClearTimeout);
            oFuncionariosClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oFuncionarios', 4);

                var oSettings = oFuncionarios.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oFuncionarios.fnDraw();
                oFuncionarios.fnFilter(that.value, oFuncionarios.oApi._fnVisibleToColumnIndex(oFuncionarios.fnSettings(), $("#oFuncionarios thead input").index(that)));

            }, 3000);

        });

        $("#oFuncionarios thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oFuncionarios thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oFuncionarios thead input").not(this).val('');
            }
        });
        $("#oFuncionarios thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oFuncionarios thead input").not(this).val(this.initVal);
            }
        });
        //oFuncionarios.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oFuncionarios'))
            {
                oTableCustomLoader('#oFuncionarios', 4);
                oFuncionarios.fnPageChange(_targetPage, true);
            }

        });
    });
    $(function() {

        $("#txtusuario").keyup(function() {
            $("#txtusuario").val($("#txtusuario").val().toUpperCase());
        });

        $("#TbAssociadoFuncionario_nome").keyup(function() {
            $("#TbAssociadoFuncionario_nome").val($("#TbAssociadoFuncionario_nome").val().toUpperCase());
        });

        // Validation            
        $("#clientefuncionario-form").validate({
            // Rules for form validation
            rules: {
                'txtUsuario': {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                'TbAssociadoFuncionario[nome]': {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                'TbAssociadoFuncionario[email]': {
                    required: true,
                    email: true
                },
                'ddlNivel': {
                    required: true,
                    minlength: 1,
                    maxlength: 255
                }
            },
            // Messages for form validation
            messages: {
                'txtUsuario': {
                    required: 'Digite o usuário',
                    minlength: 'O usuário deve ter no mínimo 03 caracteres',
                    maxlength: 'O usuário não pode ultrapassar 50 caracteres'
                },
                'TbAssociadoFuncionario[nome]': {
                    required: 'Digite o nome',
                    minlength: 'O nome deve ter no mínimo 03 caracteres',
                    maxlength: 'O nome não pode ultrapassar 50 caracteres'
                },
                'TbAssociadoFuncionario[email]': {
                    required: 'Digite o e-mail',
                    email: 'Informe um e-mail válido'
                },
                'ddlNivel': {
                    required: 'Escolha o nível de acesso',
                    SelectName: {valueNotEquals: ""}
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

                var usuario = $("#txtUsuario").val();
                var senha = $("#txtSenha").val();
                var nome = $("#TbAssociadoFuncionario_nome").val();
                var email = $("#TbAssociadoFuncionario_email").val();
                var nivel = $("#ddlNivel").val();

                var url = "";

<?php if ($page == 'create') { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/associadosfuncionario/create/ce/' . $_GET['ce']); ?>";
<?php } else { ?>
                    url = "<?php echo Yii::app()->createAbsoluteUrl('main/associadosfuncionario/update/ce/' . $_GET['ce'] . '/id/' . $_GET['id']); ?>";
<?php } ?>

                $.post(url, {usuario: usuario, senha: senha, nome: nome, email: email, nivel: nivel}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        if (data.msg == 'create') {
                            window.location = url + "/msg/" + data.msg + "/mail/" + data.mail;
                        } else {
                            window.location = url + "/msg/" + data.msg;
                        }
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
<div id="ribbon">
    <span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true"><i class="fa fa-refresh"></i></span> </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            <?php if ($page == 'create') { ?>
                Funcionário / Adicionar Novo            
            <?php } else { ?>
                Funcionário / Atualizar            
            <?php } ?>                             
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
                <?php
                $nomeRazao = "";

                $modelCli = TbAssociado::model()->findByPk($_GET['ce']);

                if ($modelCli->tipo == 'F') {
                    $nomeRazao = $modelCli->nomefantasia;
                } else {
                    $nomeRazao = $modelCli->nomerazao;
                }
                //$nomeRazao = "{Nome da Empresa Associada}";
                ?> 
                <?php if ($page == 'create') { ?>
                    <h2><i class="fa fa-edit"></i> Novo Funcionário - Associado: <strong style="color: #003bb3"> <?php echo $nomeRazao; ?> </strong> </h2>				                    
                <?php } else { ?>
                    <h2><i class="fa fa-edit"></i> Atualizar Funcionário - Associado: <strong style="color: #003bb3"> <?php echo $nomeRazao; ?> </strong> </h2>				                    
                <?php } ?> 			                    
            </header>
            <!-- widget div-->
            <div>
                <!-- content goes here -->                
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'clientefuncionario-form',
                    'htmlOptions' => array('class' => 'smart-form'),
                    'enableAjaxValidation' => false,
                ));
                ?>    
                <?php
                if (isset($_GET['msg'])) {
                    if ($_GET['msg'] == "create") {
                        $msg = "Funcionário cadastrado com sucesso!";
                        $infoMail = $_GET['mail'];
                    } elseif ($_GET['msg'] == "update") {
                        $msg = "Funcionário atualizado com sucesso!";
                    } elseif ($_GET['msg'] == "inactivate") {
                        $msg = "Funcionário inativado com sucesso!";
                    }
                }
                if (isset($erro) && !empty($erro)) {
                    ?>
                    <div id="msgWarning" class="alert alert-warning fade in" >
                        <button class="close" data-dismiss="alert">×</button>
                        <i class="fa-fw fa fa-warning"></i>
                        <strong>Atenção!</strong><br><br>
                        <?php echo $erro; ?>
                    </div>                     
                    <?php
                } elseif (isset($msg) && !empty($msg)) {
                    ?>
                    <div id="msgSuccess" class="alert alert-success fade in">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa-fw fa fa-check"></i>            
                        <?php echo $msg; ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['msg']) && $_GET['msg'] == "create") {
                    ?>            
                    <div class="alert fade in alert-info">
                        <button class="close" data-dismiss="alert"> × </button>
                        <i class="fa fa-fw fa-info-circle"></i>
                        <strong>Infomações sobre a conta do funcionário!</strong><br><br>
                        <strong>ATENÇÃO: </strong>É importante atualizar a senha.<br><br>
                        <i class="fa-fw fa fa-circle-o"></i> Foi enviado para Um e-mail para 
                        <strong><?php echo $infoMail; ?> </strong> 
                        com as informações de ativação da conta do funcionário.                        
                    </div>                     
                <?php } ?>
                <div id="msgWarning" class="alert alert-warning fade in" style="display: none">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-warning"></i>
                    <strong>Atenção!</strong><br><br>
                    <span id="msgW"></span>
                </div> 
                <!-- widget content -->
                <div class="widget-body no-padding">

                    <header >
                        <label class="text-danger">Os campos com * são obrigatórios.</label>
                    </header>
                    <fieldset>
                        <div class="row">
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'nome'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-user"></i>
                                    <?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 50)); ?>
                                </label>
                            </section>
                            <section class="col col-lg-4">
                                <label class="label"><?php echo $form->labelEx($model, 'email'); ?></label>
                                <label class="input"> <i class="icon-append fa  fa-envelope"></i>
                                    <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 50)); ?>
                                </label>
                            </section>
                            <?php if ($page == "create") { ?>
                                <section class="col col-lg-4">
                                    <label class="label">Nível de acesso *</label>
                                    <label class="input"> <i class="icon-append fa  fa-envelope"></i>                                                                               
                                        <?php
                                        $criteria = new CDbCriteria();
                                        if (Yii::app()->user->idnivel == "1") {

                                            $criteria->order = "id DESC";
                                            $modelNivel = TbNivel::model()->findAll($criteria);
                                        } else {

                                            $criteria->condition = "id<>:id ORDER BY id DESC";
                                            $criteria->params = array(":id" => "1");

                                            $modelNivel = TbNivel::model()->findAll($criteria);
                                        }
                                        $list = CHtml::listdata($modelNivel, "id", "nivel");
                                        ?>
                                        <?php echo CHtml::dropDownList('ddlNivel', '', $list, array('id' => 'ddlNivel', 'class' => 'select2')); ?>                                            
                                    </label>
                                </section>
                            <?php } ?>
                        </div>
                        <?php if ($page == "create") { ?>
                            <div class="row">
                                <section class="col col-lg-4">
                                    <label class="label">Usuário *</label>
                                    <label class="input"> <i class="icon-append fa  fa-user"></i>
                                        <input id="txtUsuario" name="txtUsuario" type="text" size = "60" maxlength = "30" >                                                                      
                                    </label>
                                </section>
                                <section class="col col-lg-4">
                                    <label class="label">Senha</label>
                                    <label class="input"> <i class="icon-append fa  fa-lock"></i>
                                        <input id="txtSenha" name="txtSenha" type="password" size = "60" maxlength = "255" >
                                    </label>
                                </section>                            
                            </div>                       
                        <?php } ?>
                        <footer>                        
                            <button id="btnCadastro" type="submit" class="btn medium btn-primary">
                                <i class="fa fa-check"></i> 
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
                            <button id="btnVoltar" class="btn medium btn-default" onclick="voltar();" type="button"><i class="fa fa-reply"></i> Voltar</button>
                        </footer>
                        <br>
                        <section class="col-xs-12">
                            <label class="label"> <div class="text-success">
                                    <strong style="font-size: 20px!important;">&nbsp;<i class="fa fa-table"></i>&nbsp;LISTA DOS FUNCIONÁRIOS CADASTRADOS </strong>
                                </div>
                            </label>                            
                            <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->                                
                            <table id="oFuncionarios" name="oFuncionarios" class="table table-striped responsive table-bordered" ><!-- style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;" -->
                                <thead>
                                    <tr>
                                        <th class="style-table-codigo">CÓDIGO</th>
                                        <th>NOME</th>
                                        <th>E-MAIL</th>                                        
                                        <th class="style-table-acoes">AÇÕES </th>
                                    </tr>
                                    <tr class="second" style="display: none" >
                                        <td></td>
                                        <td></td>
                                        <td></td>                                       
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <!-- FIM DA DATATABLE -->                            
                        </section>
                    </fieldset>
                </div>              

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </section>
</div>
<script>
    function voltar() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/associadosfuncionario/index';
    }
    function inativar(id) {

        $.SmartMessageBox({
            title: "<span><i class='fa fa-ban txt-color-red' ></i><strong class='txt-color-orange'> Inativar</strong></span>",
            content: "Deseja inativar o funcionário associado?",
            buttons: '[Não][Sim]'
        }, function(ButtonPressed) {
            if (ButtonPressed === "Sim") {
                $.smallBox({
                    title: "<i class='fa fa-spinner fa-spin'></i> Aguarde...",
                    content: "<i>Estamos inativando o funcionário associado, <br />Este processo pode demorar um pouco</i>",
                    color: "#3276B1",
                    iconSmall: "fa fa-clock-o fa-2x fadeInRight animated",
                    timeout: 99999//4000
                });

                var url = '<?= Yii::app()->createAbsoluteUrl("main/associadosfuncionario/inactivate") ?>';
                $.get(url, {id: id}, function(data) {

                    if (data.tipo == "SUCESSO") {
                        window.location = document.URL + '/msg/inactivate';
                    } else {

                        $("#msgW").html(data.msg);
                        $("#msgWarning").show();
                    }

                }, "json");
            }
        });
    }
</script>
