<?php

class DocumentoController extends Controller {

    public static function actionCarregarcustomizacao($idtipodoc){
        
        die ('tipodoc-> ' . $idtipodoc);
        
        /*$criteria = new CDbCriteria;
        
        $criteria->condition = 'idtipodoc=:idtipodoc AND ORDER BY ordem';
        $criteria->params =  array(':idtipodoc' => (int) $_POST["idtipodoc"]);
        
        $dados = TbCustomizacao::model()->findAll($criteria);

        $arrayCust = array();
        $i = 0;
        foreach ($dados as $d) {
            
            $arrayCust[$i]['titulocampo'] = $d->titulocampo;
            $arrayCust[$i]['nomecampo'] = $id->nomecampo;
            $i++;
        }*/

        //Yii::app()->end(json_encode($arrayCust));
    }
}
