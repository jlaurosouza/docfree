<html>
    <div class="jarviswidget" id="widget-id-1" >
        <header>
            <i class="fa fa-lg fa-fw icon-search"></i>
            <strong>Pesquisa Documental </strong>    
            <div class="jarviswidget-ctrls" role="menu">
                <a class="logout-js button-icon jarviswidget-delete-btn" href="<?php echo Yii::app()->request->baseUrl; ?>/login/default/logout">
                    <span class="icon-share"></span>
                </a>
                <label id="logout" class="logout-js btn-header transparent pull-right" href="<?php echo Yii::app()->request->baseUrl; ?>/login/default/logout">
                    Sair &nbsp;
                </label>
            </div>
        </header>
        <div class="">
            <!-- Success states for elements -->
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'home-form',
                'htmlOptions' => array('class' => 'form-signin'),
                'enableAjaxValidation' => false,
            ));
            ?>  

            <label id="codigoSetor" style="display: none"></label>
            <label id="codigoTd" style="display: none"></label>
            <fieldset >	
                <div class="control-group">
                    <div class="row-fluid">
                        <section class="span6">
                            <label class="control-label" >Departamento:
                                <div class="controls">
                                    <?php
                                    $criteria = new CDbCriteria();

                                    $modelDep = TbSetor::model()->findAll();
                                    foreach ($modelDep as $md){
                                        
                                        $criteria->condition = 'iddepartamento=:iddepartamento';
                                        $criteria->params = array(':iddepartamento' => $md->id);
                                        $criteria->order = "idtipodocumental";
                                        
                                        echo "Departamento: " . $md->setor . "<p>";
                                        echo '---------------------------------------------------------------------------------------------------------------------------------------<p>';
                                        
                                        $modelTipoDoc = TbSetorTipodoc::model()->findAll($criteria);
                                        
                                        foreach ($modelTipoDoc as $mt){
                                            
                                            $criteria->condition = 'idtipodoc=:idtipodoc';
                                            $criteria->params = array(':idtipodoc' => $mt->idtipodocumental);
                                            $criteria->order = "ordem";
                                            
                                            echo "Tipo Documental: " . $mt->tipodocumental . "<p>";
                                            
                                            $modelCust = TbCustomizacao::model()->findAll($criteria);
                                            
                                            $listaCust = '';
                                            foreach ($modelCust as $mc){
                                                if (empty($listaCust)) {
                                                    $listaCust =  $mc->titulocampo;
                                                }else{
                                                    $listaCust .=  "_" . $mc->titulocampo;
                                                }
                                            }
                                            echo "Modelo para Indexação: " . $listaCust . '<p>';
                                            echo '---------------------------------------------------------------------------------------------------------------------------------------<p>';
                                        }
                                    }
                                    ?>
                                </div>
                            </label>
                        </section>
                    </div> 
                </div>	
            </fieldset>            
            <!-- FIM ADD -->                
            <?php $this->endWidget(); ?>    
        </div>
        <!--span id="logo-group"><img src="<-?php echo Yii::app()->request->baseUrl; ?>/assets/img/docfreerodape_.png" alt="Docfree"> </span>
    </div-->
</html>
