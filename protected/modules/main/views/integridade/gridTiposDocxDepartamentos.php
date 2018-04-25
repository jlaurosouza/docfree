<script type="text/javascript">

    function oIntegracaoCallback(json) {

        if (!$('#main #oIntegracaooCallback').length)
        {
            $('#main').append('<div id="oIntegracaooCallback"></div>');
        }

        $('#oIntegracaooCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oIntegracao
         */
        var oIntegracao = $('#oIntegracao').dataTable({
            "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "aaSorting": [[0, 'desc']],
            "oLanguage": {
                "sSearch": "Pesquisar todas as colunas:",
                "sEmptyTable": "Não há Tipo documental relacionados a pesquisa",
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/integridade/gridintegra'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 1000,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oIntegracao', 2);
                        $('.customPage.oIntegracao').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oIntegracao').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oIntegracao').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oIntegracao').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oIntegracao').fadeIn(500);
                    }

                    fnCallback(data);
                    oIntegracaoCallback(data);
                }, 'json');
            }

        });

        var oIntegracaoClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oIntegracao thead input").keyup(function() {

            clearTimeout(oIntegracaoClearTimeout);
            oIntegracaoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oIntegracao', 3);
                var oSettings = oIntegracao.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }                
                oSettings.aoPreSearchCols[0].sSearch = $("#txtDep").val();
                oIntegracao.fnDraw();
                
            }, 500);

        });

        $("#oIntegracao thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oIntegracao thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oIntegracao thead input").not(this).val('');
            }
        });
        $("#oIntegracao thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oIntegracao thead input").not(this).val(this.initVal);
            }
        });
        //oIntegracao.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oIntegracao'))
            {
                oTableCustomLoader('#oIntegracao', 2);
                oIntegracao.fnPageChange(_targetPage, true);
            }

        });
    });
</script>
<!--<style>
    .row {display: none}
</style>-->
<section class="col col-lg-5">
    <div class=" text-success">
        <strong style="font-size: 20px!important;">&nbsp;<i class="fa fa-lg fa-link"></i>&nbsp;TIPOS DOCUMENTAIS INTEGRADOS</strong>
    </div>
<!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
<table id="oIntegracao" class="table table-striped responsive table-bordered checked-in has-checkbox">
    <thead>
        <tr>
            <th>
            <th>Tipos Documentais</th>            
        </tr>    
        <tr class="second" style="display: none">
            <td>     
                <label class="input">
                    <input id="txtDep"  type="text" name="search_Dep_grid" placeholder="Departamento" class="seach_init">
                </label>
            </td> 
            <td></td>  
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</section>
<select id="listTipoIntegrado" class="span12" multiple="multiple" style="display: none;">
</select>
<script>
    function MarcarTdIntegrado(id) {
        var selectTipodoc = document.getElementById("listTipoIntegrado");
        var existe = 0;
        var remove = 0;
        if (selectTipodoc.length == 0){
            $("#listTipoIntegrado").append("<option value='" + id + "'>" + id + "</option>");
        }else{
            for (i = 0; i < selectTipodoc.length; i = i + 1) {
                if (selectTipodoc.options[i].value == id){
                   remove = i;
                   existe = 1;                   
                }            
            }
            if (existe == 0){
                $("#listTipoIntegrado").append("<option value='" + id + "'>" + id + "</option>");
            }else{
                selectTipodoc.remove(remove);
            }
        }
        
    }
</script>