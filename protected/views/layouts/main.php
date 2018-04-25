<!DOCTYPE html>
<html lang="en-us" ng-app="app">
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->            
        <title> Docfree - Gerenciamento Documental </title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Use the correct meta names below for your web application
                 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
                 
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">-->

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/smartadmin-production.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/smartadmin-skins.css">
        
        <!-- SmartAdmin RTL Support is under construction
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/css/login.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.-->

        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/breadcrumb.css"> 

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/demo.css">

        <!-- FAVICONS -->
        <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

        <!-- GOOGLE FONT 
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">-->

        <link rel="stylesheet" type="text/css" media="all" href="/assets/css/your_style.css">

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/pace/pace.min.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/libs/jquery-2.0.2.min.js"></script>        
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/libs/jquery-ui-1.10.3.min.js"></script>               


        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

        <!-- BOOTSTRAP JS -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap/bootstrap.min.js"></script>

        <!-- CUSTOM NOTIFICATION -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/notification/SmartNotification.min.js"></script>

        <!-- JARVIS WIDGETS -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/smartwidgets/jarvis.widget.min.js"></script>

        <!-- EASY PIE CHARTS -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

        <!-- SPARKLINES -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!-- JQUERY SELECT2 INPUT -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/select2/select2.min.js"></script>
       
        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

        <!-- browser msie issue fix -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

        <!-- FastClick: For mobile devices -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/fastclick/fastclick.js"></script>

        <!-- JQUERY plugin timapicker -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
        
        <!--[if IE 7]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/util.js"></script>

        <!-- Demo purpose only -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/demo.js"></script>      

        <!-- DATATABLE -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/jquery.dataTables-cust.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/ColReorder.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/FixedColumns.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/ColVis.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/ZeroClipboard.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/media/js/TableTools.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/plugin/datatables/DT_bootstrap.js"></script>
        <!-- FIM DATATABLE -->

        <script type="text/javascript">

            function oTableCustomLoader(id, colspan) {

                $(id + ' tbody').html('<tr class="tr-loading"><td align="center" colspan="' + colspan + '"><i class="fa fa-refresh fa-spin"></i> Por favor, aguarde...</td></tr>');
            }

            function oTableZeroRecords(id, colspan) {

                $(id + ' tbody').html('<tr class="tr-loading"><td align="center" colspan="' + colspan + '"><i class="fa fa-bell"></i> Nada encontrado: Não há resultados disponíveis. </td></tr>');
            }
        </script>

    </head>
    <body id="bodymain" class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">

                <!-- PLACE YOUR LOGO HERE style="display: inline-block;width: 100px;margin-left: 0px;" /// style="display: inline-block;width: 100px;margin-top: -11px;margin-left: 0px;" -->                
                <span id=""  style="display: inline-block;width: 100px;margin-top: 3px;margin-left: 40px;">                     
                    <img style="width: 120%;height: auto;padding-left: 10px;" class="imgContraste" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo/logo.png" alt="docfree">
                </span>
                <!-- END LOGO PLACEHOLDER -->

                <!-- Note: The activity badge color changes when clicked and resets the number to 0
                Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
                <!--<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 0 </b> </span>

                <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
                <div class="ajax-dropdown">

                    <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="">
                            Notificações (0) </label>                        
                    </div>

                    <!-- notification content -->
                    <div class="ajax-notifications custom-scroll">
                        <div class="alert alert-transparent">
                            <h4>Clique no botão acima para exibir as notificações</h4>                            
                        </div>
                        <i class="fa fa-lock fa-4x fa-border"></i>
                    </div>
                    <!-- end notification content -->

                    <!-- footer: refresh area -->
                    <span> 
                        <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Carregando..." class="btn btn-xs btn-default pull-right">
                            <i class="fa fa-refresh"></i>
                        </button> </span>
                    <!-- end footer -->

                </div>
                <!-- END AJAX-DROPDOWN -->
            </div>            

            <!-- pulled right: nav area -->
            <div class="pull-right">

                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->

                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo Yii::app()->request->baseUrl; ?>/login/default/logout" title="Sair"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <!-- end logout button -->

            </div>
            <!-- end pulled right: nav area -->

        </header>
        <!-- END HEADER -->

        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <!--<-?php if (Yii::app()->user->tipousuario == 'O'){ ?>-->
        <aside id="left-panel">

            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as it --> 

                    <a href="javascript:void(0);" id="show-shortcut">                
                        <span>
                            <?php echo Yii::app()->user->nome; ?>
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 

                </span>
            </div>            
            <nav>
                <?php $this->renderPartial('//layouts/_menu', array()); ?>    
            </nav>           
            <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>            
            <div id="hide-menu" >
                <span id="" style="display: inline-block;width: 175px;margin-top: -11px;margin-left: 10px;" > 
                    <div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>
                    <!--                    <div >
                                            <img style="width: 200px;height: auto;padding-left: 0px;" src="<-?php echo Yii::app()->request->baseUrl; ?>/images/logo/logo-fiabilite.png" alt="fiabilite">
                                        </div>-->
                    <div>
                        <img style="width: 200px;height: auto;padding-left: 0px;" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo/logo.png" alt="Macedo Rocha">                        
                    </div>                   
                </span>
            </div>
        </aside>
        <!-- <-?php } ?>
        <!-- END NAVIGATION -->

        <!-- MAIN PANEL -->
        <div id="main" role="main">            
            <?php
            echo $content;
            ?>
        </div>
        <!-- END MAIN PANEL -->

        <!--================================================== -->

        <!-- MAIN APP JS FILE -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app.js"></script>
        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!	
            $(document).ready(function() {
                pageSetUp();
            });</script>
        <script>
            $('#content').show();
            $('.jarviswidget').show();
            $(document).ready(function() {
<?php
echo "activeMenu();";
switch (Yii::app()->controller->id) {
    case 'indexacao':
    case 'pesquisar':
       echo "$('.a-cadastros').trigger('click');";
        break;
    case 'associados':
    case 'associadosfuncionario':
        echo "$('.a-associado').trigger('click');";       
        break;
    case 'usuarios':
    case 'nivelacesso':
    case 'controleacesso':
        echo "$('.a-usuarios').trigger('click');";
        break;
    case 'departamento':
    case 'tipodocumento':
    case 'integridade':
    case 'customizacao':
         echo "$('.a-gerencia').trigger('click');";
        break;    
}
?>
            });</script>

        <script type="text/javascript">

            // DO NOT REMOVE : GLOBAL FUNCTIONS!

            $(document).ready(function() {

                pageSetUp();
                // PAGE RELATED SCRIPTS

                $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
                $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
                    var children = $(this).parent('li.parent_li').find(' > ul > li');
                    if (children.is(':visible')) {
                        children.hide('fast');
                        $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
                    } else {
                        children.show('fast');
                        $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
                    }
                    e.stopPropagation();
                });
            });</script>

        <!--        Your GOOGLE ANALYTICS CODE Below 
                <script type="text/javascript">
                    var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
                    _gaq.push(['_trackPageview']);
                    (function() {
                        var ga = document.createElement('script');
                        ga.type = 'text/javascript';
                        ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(ga, s);
                    })();
                </script>        -->

        <!-- 
        /*
         * autor: jlaurosouza
         * atualizado por: 
         * data criação: 24/04/2017
         * data última atualização: 24/04/2017 
         * descrição: 
         *      Aplica a seta "<" ao lado do menu escolhido 
         */
        -->
        <script type="text/javascript">
            function activeMenu() {
                var url = window.location.href;
                var absoluto = url.split("/")[url.split("/").length - 1];

                var controller = '<?= Yii::app()->controller->id; ?>';
                if (controller == 'usuarios') {
                    if (absoluto == 'redefinirsenha') {
                        $("#usuariosrd").addClass("active");
                    } else {
                        $("#" + controller).addClass("active");
                    }
                } else {
                    $("#" + controller).addClass("active");
                }
            }
        </script>
    </body>

</html>