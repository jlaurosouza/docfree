<script type="text/javascript">

    function oTipodocumentoCallback(json) {

        if (!$('#main #oTipodocumentooCallback').length)
        {
            $('#main').append('<div id="oTipodocumentooCallback"></div>');
        }

        $('#oTipodocumentooCallback').html(json.modal);
    }

    $(document).ready(function() {

        /*
         * Aguardando Resolução oTipodocumento
         */
        var oTipodocumento = $('#oTipodocumento').dataTable({
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
            "sAjaxSource": "<?php echo Yii::app()->createAbsoluteUrl('/main/tipodocumento/gridintegra'); ?>",
            "sServerMethod": "POST",
            "iDisplayLength": 1000,
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                cacheData = aoData;
                oSettings.jqXHR = $.post(sSource, cacheData, function(data) {

                    if (!parseInt(data.iTotalRecords) > 0)
                    {
                        oTableZeroRecords('#oTipodocumento', 2);
                        $('.customPage.oTipodocumento').fadeOut(500);
                    }

                    else
                    {
                        $('.customPagination.oTipodocumento').children('label').children('input').attr('disabled', false).val(data.iDisplayStart);
                        $('.customPagination.oTipodocumento').children('span').html(Math.ceil(parseInt(data.iTotalRecords) / parseInt(data.iDisplayLength)));
                        $('.customPagination.oTipodocumento').children('button').attr('disabled', false).css('cursor', 'pointer').html('<i class="fa fa-arrow-circle-right"></i> Ir a Página');
                        $('.customPagination.oTipodocumento').fadeIn(500);
                    }

                    fnCallback(data);
                    oTipodocumentoCallback(data);
                }, 'json');
            }

        });

        var oTipodocumentoClearTimeout = null;
        /* Add the events etc before DataTables hides a column */
        $("#oTipodocumento thead input").keyup(function() {

            var that = this;
            clearTimeout(oTipodocumentoClearTimeout);
            oTipodocumentoClearTimeout = setTimeout(function() {

                oTableCustomLoader('#oTipodocumento', 2);

                var oSettings = oTipodocumento.fnSettings();
                for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                    oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                //oTipodocumento.fnDraw();
                oTipodocumento.fnFilter(that.value, oTipodocumento.oApi._fnVisibleToColumnIndex(oTipodocumento.fnSettings(), $("#oTipodocumento thead input").index(that)));

            }, 3000);

        });

        $("#oTipodocumento thead input").each(function(i) {
            this.initVal = this.value;
        });
        $("#oTipodocumento thead input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
                $("#oTipodocumento thead input").not(this).val('');
            }
        });
        $("#oTipodocumento thead input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = this.initVal;
                $("#oTipodocumento thead input").not(this).val(this.initVal);
            }
        });
        //oTipodocumento.fnDraw();

        $('.customPagination').submit(function(e) {

            var _input = $(this).children('label').children('input');
            var _button = $(this).children('button');
            var _targetPage = _input.val().length > 0 ? parseInt(_input.val()) - 1 : 0;
            _input.attr('disabled', true);
            _button.attr('disabled', true).css('cursor', 'wait').html('<i class="fa fa-refresh fa-spin"></i> Carregando...');
            if ($(this).hasClass('oTipodocumento'))
            {
                oTableCustomLoader('#oTipodocumento', 2);
                oTipodocumento.fnPageChange(_targetPage, true);
            }

        });
//        $(".dt-row dt-bottom-row").change(function(){
//            ('.row text-right').removeClass('col-sm-6');
//            ('.row text-right').addClass('col-sm-12');
//        });
    });
</script>
<section class="col col-lg-5">
    <div class=" text-success">
        <strong style="font-size: 20px!important;">&nbsp;<i class="fa fa-table"></i>&nbsp;TODOS OS TIPOS DOCUMENTAIS </strong>
    </div>
    <!-- INICIO DA DATATABLE  table table-striped table-bordered responsive dataTable -->
    <table id="oTipodocumento" name="oTipodocumento" class="table table-striped responsive table-bordered dataTable" >
        <thead>
            <tr>
                <th>
                <th>Tipos Documentais</th>            
            </tr>        
        </thead>
        <tbody>
            <tr class="second" style="display: none" >
                <td></td>
                <td></td>            
            </tr>
        </tbody>
    </table>
</section>
<select id="listTipoDoc" class="span12" multiple="multiple" style="display: none;">    
</select>
<script>
    function MarcarTdoc(id) {
        var selectTipodoc = document.getElementById("listTipoDoc");
        var existe = 0;
        var remove = 0;
        if (selectTipodoc.length == 0) {
            $("#listTipoDoc").append("<option value='" + id + "'>" + id + "</option>");
        } else {
            for (i = 0; i < selectTipodoc.length; i = i + 1) {
                if (selectTipodoc.options[i].value == id) {
                    remove = i;
                    existe = 1;
                }
            }
            if (existe == 0) {
                $("#listTipoDoc").append("<option value='" + id + "'>" + id + "</option>");
            } else {
                selectTipodoc.remove(remove);
            }
        }
    }
</script>