<script type="text/javascript">
    /* == Função Carrega ddlTipodoc == */
    function carregarTipoDoc() {

        if ($("#ddlDepartamento option:selected").val() > 0) {
            $("#ddlTipodoc").html("");
            $("#ddlTipodoc").append("<option value='0'>carregando...</option>");
            $.post("<?= Yii::app()->createAbsoluteUrl('/main/default/carregarTipodoc/') ?>", {codigoDepartamento: $("#ddlDepartamento option:selected").val()}, function(data) {
                $("#ddlTipodoc").html("");
                for (i = 0; i < data.length; i++) {
                    $("#ddlTipodoc").append("<option value='" + data[i].id + "'>" + data[i].tipodocumento + "</option>");
                }

                /*var codigoTipodoc = parseInt('<-?= $model->idtipodoc ?>');
                 if (codigoTipodoc > 0) {
                 $("#ddlTipodoc option[value='<-?= $model->idtipodoc ?>']").attr("selected", true);
                 }*/

            }, "json");
        }
    }
    /* == Fim Função == */

    /* == Função Selecionar Documento == */
    $(function() {

        var div = document.getElementsByClassName("botaoArquivo")[0];
        var input = document.getElementById("inputArquivo");

        div.addEventListener("click", function() {
            input.click();
        });
        input.addEventListener("change", function() {
            var nome = "Não há arquivo selecionado. Selecionar arquivo...";
            if (input.files.length > 0)
                nome = input.files[0].name;
            div.innerHTML = "&nbsp;" + nome + "<span  class='file-action btn btn-primary' style='width: 220px;'>" +
                    " <i class='icon-folder-open'></i> &nbsp;Selecione um Documento " +
                    "<input class='file-action btn btn-primary'   type='file' id='inputArquivo'>" +
                    "</span>"
        });
    });
    /* == Fim da Função Selecionar Documento */
</script>
<h1 id="page-header">Novo Documento</h1>	

<div class="fluid-container">
    <!-- row-fluid -->
    <div class="row-fluid">
        <!-- new widget -->
        <div class="jarviswidget" id="widget-id-1">
            <header>
                <h2><i class="icon-list"></i> Adicionar Novo Documento</h2>                           
            </header>
            <!-- wrap div -->
            <div>
                <!-- content goes here -->
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'home-form',
                    'htmlOptions' => array('class' => 'form-horizontal themed'),
                    'enableAjaxValidation' => false,
                ));
                ?>  

                <div class="widget-body no-padding">
                    <header class="">
                        <h3>&nbsp; Os campos com * são obrigatórios.</h3>
                    </header>
                    <div class="invoice-client-info"></div>
                    <div class="inner-spacer">
                        <fieldset id="select-demo-js">
                            <div class="control-group">
                                <div class="fluid-container">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <label for="inputArquivo">Nome do Documento *</label>
                                            <div class="uploader botaoArquivo filenamenew">&nbsp; Nenhum documento selecionado.                                    
                                                <span  class="file-action btn btn-primary" style="width: 220px;">
                                                    <i class="icon-folder-open"></i> &nbsp;Selecione um Documento
                                                    <input class="file-no-style file-action btn btn-primary"   type="file" id="inputArquivo">
                                                </span>
                                            </div>  
                                            <div class="note" style="color:blue;">Apenas o tipo PDF são suportados.</div>
                                            <!--div class="note">Tipos suportados: PDF, PNG, GIF, JPG, JPEG.</div-->
                                        </div>
                                    </div>
                                </div>
                                <div>&nbsp;</div>
                                <div class="fluid-container">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <label class="">Departamento</label>
                                            <label class="select"> <i class="icon-append fa"></i>
                                                <?php
                                                $modelDepartamento = TbDepartamento::model()->findAll();
                                                $list = CHtml::listdata($modelDepartamento, "id", "departamento");
                                                ?>
                                                <?php echo CHtml::dropDownList('ddlDepartamento', '', $list, array('id' => 'ddlDepartamento', 'empty' => '••• Selecione o departamento desejado •••', 'onchange' => 'carregarTipoDoc()', 'class' => 'span12')); ?>
                                            </label>
                                        </div>
                                        <div class="span6">
                                            <label class="">Tipo Documental</label>
                                            <label class="select"> <i></i>
                                                <?php
                                                $list = array();
                                                echo CHtml::dropDownList('ddlTipodoc', '', $list, array('id' => 'ddlTipodoc', 'empty' => '', 'class' => 'span12'));
                                                //echo $form->dropDownList('ddlTipodoc', '', array('class' => 'span12'), array('empty' => '')); 
                                                ?> 
                                            </label>
                                        </div>
                                    </div>                                    
                                </div>                               
                                <div>&nbsp;</div>
                            </div>
                            <div class="form-actions">    
                            <button id="btnCancel" class="btn medium btn-default" onclick="Cancel();" type="button">Cancelar</button>
                            <button id="btnCadastro" type="submit" class="btn medium btn-primary">
                               Cadastrar                                           
                            </button>
                            <img style="display: none" id="loading" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/img/ajax-loader.gif" >
                        </div>
                        </fieldset>                         
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    function Cancel() {
        window.location = '<?php echo Yii::app()->request->baseUrl; ?>/main/default/index';
    }
</script>