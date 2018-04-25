<script type="text/javascript">

    function oDepartamentoCallback(json) {

        if (!$('#main #oDepartamentooCallback').length)
        {
            $('#main').append('<div id="oDepartamentooCallback"></div>');
        }

        $('#oDepartamentooCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oDepartamento
         */
        var oDepartamento = $('#oDepartamento').dataTable({
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/departamento/gridintegra'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 1000,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oDepartamento', 3);
                        $('.customPage.oDepartamento').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oDepartamento').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oDepartamento').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oDepartamento').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oDepartamento').fadeIn(500);
                    }

                    fnCallback(data);
                    oDepartamentoCallback(data);
                }, 'json');
            }

        });

        var oDepartamentoClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oDepartamento thead input").keyup(function() {

            var that = this;
            clearTimeout(oDepartamentoClearTimeout);
            oDepartamentoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oDepartamento', 3);

                var oSettings = oDepartamento.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oDepartamento.fnDraw();
                oDepartamento.fnFilter(that.value, oDepartamento.oApi._fnVisibleToColumnIndex(oDepartamento.fnSettings(), $("#oDepartamento thead input").index(that)));

            }, 3000);

        });

        $("#oDepartamento thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oDepartamento thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oDepartamento thead input").not(this).val('');
            }
        });
        $("#oDepartamento thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oDepartamento thead input").not(this).val(this.initVal);
            }
        });
        //oDepartamento.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oDepartamento'))
            {
                oTableCustomLoader('#oDepartamento', 3);
                oDepartamento.fnPageChange(_targetPage, true);
            }

        });
    });
</script>
<style>
    .row {display: none}
</style>
<header role="heading">
    <div style="font-weight: bold;">Todos os Departamentos</div>    
</header>
<!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
<table id="oDepartamento" class="table table-striped responsive table-bordered checked-in has-checkbox">
    <thead>
        <tr>
            <th class="first">
            <th ><label style="text-overflow: ellipsis; font-weight: bold;">Departamentos</label></th>            
        </tr>        
    </thead>
    <tbody>
        <tr class="second" style="display: none" >
            <td></td>
            <td></td>            
        </tr>
    </tbody>
</table>