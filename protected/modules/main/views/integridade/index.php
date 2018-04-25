<script type="text/javascript">
    $(document).ready(function() {
    });
    function carregagridintegracao() {
        var codigodep = $("#ddl-dep").val();
        if (codigodep == "") {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione o Departamento!</span>",
                content: "Para criar uma integração é necessário selecionar um departamento.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                    $("#txtDep").val(0);
                    $("#oIntegracao thead input").trigger('keyup');
                }
            });
        } else {
            $("#txtDep").val(codigodep);
            $("#oIntegracao thead input").trigger('keyup');
        }

    }
</script>
<!-- RIBBON -->
<div id="ribbon">
    <span class="ribbon-button-alignment"> 
        <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Atenção! Você perderá os dados não salvos." data-html="true">
            <i class="fa fa-refresh"></i>
        </span> 
    </span>
    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li>
            Integração Gerêncial
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" > 
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-12">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-link"></i>
                Integração Gerêncial
                <span>> Crie ou visualize uma Integração Gerencial </span>
            </h1>
        </div>        
    </div>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'home-form',
        'htmlOptions' => array('class' => 'form-horizontal themed'),
        'enableAjaxValidation' => false,
    ));
    ?> 
    <section id="widget-grid">   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Tabelas para integração gerencial</h2>				                    
            </header>
            <div class="smart-form">
                <div class="row">               
                    <section class="col col-4">
                        <label class="label">Todos os Departamentos</label>
                        <label class="select"> <i class="icon-append fa"></i>
                            <?php
                            $tipodoc = TbDepartamento::model()->findAll();
                            $list = CHtml::listdata($tipodoc, "id", "departamento");
                            echo CHtml::dropDownList('ddl-dep', $list, $list, array('empty' => 'Selecione o departamento', 'onchange' => 'carregagridintegracao()', 'class' => 'select2'));
                            ?> 
                        </label>
                    </section>
                </div>
                <header style="margin: -10px 0 0!important;" ></header>
                <br>
                <div class="row">                                   
                    <?php $this->renderPartial('gridTiposDocumentais', array()); ?>

                    <section class="col col-lg-2" >
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <center>
                            <a class="btn btn-lg btn-primary" href="javascript:void(0);" onclick="AddIntegridade();">Adicionar <i class="fa fa-arrow-right"></i></a>
                        </center>
                        &nbsp;
                        <center>
                            <a class="btn btn-lg btn-primary" href="javascript:void(0);" onclick="RemoverIntegridade();"><i class="fa fa-arrow-left"></i> Remover</a>
                        </center>
                    </section>
                    <?php $this->renderPartial('gridTiposDocxDepartamentos', array()); ?> 
                </div>
                <div class="row">                                   

                </div>
            </div>
        </div>
    </section>
    <?php $this->endWidget(); ?>
</div>
<script>
    function AddIntegridade() {
        var selectTipodoc = document.getElementById("listTipoDoc");

        if ($("#txtDep").val() == 0) {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione o Departamento!</span>",
                content: "Para criar uma integração é necessário selecionar um departamento.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                }
            });
        } else {
            if (selectTipodoc.length == 0) {
                $.SmartMessageBox({
                    title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione no mínimo um tipo documental!</span>",
                    content: "Para criar uma integração é necessário selecionar tipo documental.",
                    buttons: '[Ok]'
                }, function(ButtonPressed) {
                    if (ButtonPressed === "Ok") {
                    }
                });
            } else {
                var i;
                for (i = 0; i < selectTipodoc.length; i = i + 1) {
                    $.post("<?= Yii::app()->createAbsoluteUrl('/main/integridade/adicionarintegridade/') ?>", {idtipodoc: selectTipodoc.options[i].value, iddepartamento: $("#txtDep").val()}, function(data) {
                    }, "json");
                }
                $("#txtDep").val($("#ddl-dep").val());
                $("#oIntegracao thead input").trigger('keyup');
            }
        }
    }

    function RemoverIntegridade() {
        var selectTipoIntegrado = document.getElementById("listTipoIntegrado");

        if ($("#txtDep").val() == 0) {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione o Departamento!</span>",
                content: "Não existe departamento selecionado.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                }
            });
        } else {
            if (selectTipoIntegrado.length == 0) {
                $.SmartMessageBox({
                    title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione no mínimo um tipo documental integrado!</span>",
                    content: "Para remover uma integração é necessário selecionar tipo documental.",
                    buttons: '[Ok]'
                }, function(ButtonPressed) {
                    if (ButtonPressed === "Ok") {
                    }
                });
            } else {
                var i;
                for (i = 0; i < selectTipoIntegrado.length; i = i + 1) {
                    $.post("<?= Yii::app()->createAbsoluteUrl('/main/integridade/removerintegridade/') ?>", {id: selectTipoIntegrado.options[i].value}, function(data) {
                    }, "json");
                }
                for (i = 0; i < selectTipoIntegrado.length; i = i + 1) {
                    selectTipoIntegrado.remove(i);
                }

                $("#txtDep").val($("#ddl-dep").val());
                $("#oIntegracao thead input").trigger('keyup');
            }
        }
    }
</script>
