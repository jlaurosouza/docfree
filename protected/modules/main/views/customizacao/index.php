<script type="text/javascript">
    $(document).ready(function() {
    });
    function cadastrarnovacustomizacao() {

        var codigotipodoc = $("#ddl-tipodoc").val();
        if (codigotipodoc == "") {
            $.SmartMessageBox({
                title: "<i class='fa fa-info-circle txt-color-orangeDark'></i><span > Selecione o Tipo Documental!</span>",
                content: "Para cadastrar um novo campo de customização é necessário selecionar um tipo documental.",
                buttons: '[Ok]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Ok") {
                }
            });
        } else {
            window.location = '<?= Yii::app()->createAbsoluteUrl("main/customizacao/create/ce/") ?>/' + codigotipodoc;
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
            Customização 
        </li>
    </ol>
    <!-- end breadcrumb -->               
</div>
<div id="content" >    
    <div class="row">
        <!--<div class="col-xs-12 col-sm-9 col-md-9 col-lg-6">-->
        <div class="col col-lg-5">
            <h1 class="page-title txt-color-blueDark">
                <i class="fa fa-lg fa-fw fa-puzzle-piece"></i>
                Customização
                <span>> Lista das Customizações </span>
            </h1>
        </div>
        <div class="row smart-form pull-right">   
            <label class='input-sm'>
                <section class="col col-lg-5" >
                    <label class ='label'>Todos os Tipos documentais</label>
                    <label class="select"> <i class="icon-append fa"></i>
                        <?php
                        $tipodoc = TbTipodocumento::model()->findAll(array('order' => 'nome ASC'));
                        $list = CHtml::listdata($tipodoc, "id", "nome");
                        echo CHtml::dropDownList('ddl-tipodoc', $list, $list, array('empty' => 'Selecione o tipo documental', 'onchange' => 'carregarTipoDocumento()', 'class' => 'select2'));
                        ?> 
                    </label>
                    <label>&nbsp;</label>
                </section>                
                <section class="col col-lg-5 ">                
                    <label class='input' style="top: 9px!important;">
                        <a class="btn btn-primary btn-lg header-btn hidden-mobile " data-toggle="modal" href="javascript:void(0)" onclick="cadastrarnovacustomizacao()">
                            <i class="fa fa-plus"></i>
                            Criar Nova Customização
                        </a>
                    </label>
                </section>
            </label>
        </div>       
    </div>
    <section id="widget-grid">   
        <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="true" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-togglebutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i> </span>
                <h2>Todos as Customizações</h2>				                    
            </header>                                  
            <div class="row" >
                <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
                <table id="oTipodocumento" name="oTipodocumento" class="table table-striped responsive table-bordered" style="border: 1px!important; border-style: solid!important; border-top-style: solid!important; border-color: #cdcdcd!important;">
                    <thead>
                        <tr>
                            <th  class="style-table-codigo">CÓDIGO</th>
                            <th>CAMPOS DO CUSTOMIZAÇÃO</th>
                            <th class="style-table-two-acoes">AÇÕES </th>
                        </tr>
                        <tr class="second" style="display: none" >
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!-- FIM DA DATATABLE -->
                <br/>
            </div>
        </div>
    </section>
</div>