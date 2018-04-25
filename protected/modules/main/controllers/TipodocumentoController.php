<?php

class TipodocumentoController extends Controller {
    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 02/12/2015
     * data última atualização: 02/12/2015 
     * descrição: 
     *      Verifica sé o usuário esta Logado, se não estiver rendiraciona o formulario "login/default/index.php"
     */

    public function init() {
        if ((Yii::app()->user->isGuest)) {
            $this->redirect(array("/login/default/index"));
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      renderiza o o formulario "main/usuarios/index.php"
     */

    public function actionIndex() {
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Direciona para o _Form se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo tipo documental a base de dados.
     *      
     */

    public function actionCreate() {

        $model = new TbTipodocumento();
        $retorno = array();

        if ($_POST) {
            
            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";
            
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {
                
                //variável referente ao usuário
                $tipoDocumental = trim($_POST['tipoDocumental']);
                if (empty($tipoDocumental)) {
                    throw new Exception("<strong>Tipo Documental</strong> não pode ser vazio.");
                }

                //restruturar o nome no tabela que será criada.
                $TebelaUtil = "dp_" . Util::spaceToUnder(Util::replaceCaracterEspecial($tipoDocumental));
                
                $model->identidade = Yii::app()->user->identidade;
                $model->nome = $tipoDocumental;
                $model->tabelautil = $TebelaUtil;
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExistenciaTipoDocumental($model->nome, 0) > 0) {
                        throw new Exception("Tipo Documental já cadastrado.");
                    }
                    if (!$model->save()) {                    
                        throw new Exception("Falha ao tentar salvar.");
                    }
                    if (!$this->createNewTable($TebelaUtil)){
                        throw new Exception("Falha ao tentar criar tabela referênte ao tipo documental.");
                    }
                    $transaction->commit();
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário.');
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $retorno['tipo'] = "error";
                $retorno['msg'] = $ex->getMessage();
               // die($ex->getMessage());
                
            }
            Yii::app()->end(json_encode($retorno));
        }
        $this->render('create', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 19/11/2015
     * data última atualização: 19/11/2015 
     * descrição: 
     *      Carrega o formuário com os dados do usuário.
     */

    public function loadModel($id) {
        $model = TbTipodocumento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/12/2015
     * data última atualização: 07/12/2015 
     * descrição: 
     *      
     */

    public function actionGrid() {

        $condition = '';
        $params = array();

        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 10;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->alias = "u";
        $criteria->select = "u.*";

        $criteria->condition = $condition;
        $criteria->params = $params;

        $result["iTotalRecords"] = TbTipodocumento::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbTipodocumento::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;

        $sort = isset($_POST['sSortDir_0']) ? trim($_POST['sSortDir_0']) : 'ASC';
        $order = isset($_POST['iSortCol_0']) ? trim($_POST['iSortCol_0']) : 'id';

        switch ($order) {
            default:
                $order = 'u.id';
                break;
        }

        $criteria->order = $order; //. ' ' . $sort;
        $criteria->limit = $rows;
        $criteria->offset = $offset;


        $model = TbTipodocumento::model()->findAll($criteria);
        $grid = array();
        $i = 0;

        foreach ($model as $m) {
            
//            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/tipodocumento/update/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->nome;
  //          $grid[2] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }
    
    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Verifica se o tipo documental já existe na base de dados.
     */

    private function verificarExistenciaTipoDocumental($TipoDoc, $codigo) {

        $criteria = new CDbCriteria();

        if ($codigo == 0) {
            $criteria->condition = "nome=:nome";
            $criteria->params = array(":nome" => $TipoDoc);
        } else {
            $criteria->condition = "nome=:nome and id<>:id";
            $criteria->params = array(":nome" => $TipoDoc, ":id" => $codigo);
        }

        return TbTipodocumento::model()->count($criteria);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Criar Nome Tabela referente ao tipo Documental.
     */

    private function createNewTable($Table) {

       Yii::app()->db->createCommand("CREATE TABLE {$Table}( id INT(11) NOT NULL AUTO_INCREMENT, identidade INT(11) not null, 
                                    iddepartamento INT(11) not null, nomedocumento VARCHAR(100) default null, lote VARCHAR(50) default null,
                                    caminhoimg VARCHAR(255) CHARACTER SET utf8 default null, status int(11), 
                                    nomeorigem varchar(255) CHARACTER SET utf8 DEFAULT NULL, idtipodocumento int(11) not null, 
                                    nometipodoc varchar(100) CHARACTER SET utf8 DEFAULT NULL, datainicio datetime DEFAULT NULL, 
                                    datahoraindex datetime DEFAULT NULL, palavrachave varchar(250) CHARACTER SET utf8 DEFAULT NULL, 
                                    pesquisarapida varchar(500) CHARACTER SET utf8 DEFAULT NULL, ocr text null, 
                                    operador varchar(50) CHARACTER SET utf8 DEFAULT NULL, 
                                    PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=latin1;")->execute();
       return true;
    }

    
    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 18/01/2017
     * data última atualização: 18/01/2017 
     * descrição: 
     *      
     */

    public function actionGridIntegra() {

        $condition = '';
        $params = array();

        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 10;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->alias = "u";
        $criteria->select = "u.*";

        $criteria->condition = $condition;
        $criteria->params = $params;

        $result["iTotalRecords"] = TbTipodocumento::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbTipodocumento::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;

        $sort = isset($_POST['sSortDir_0']) ? trim($_POST['sSortDir_0']) : 'ASC';
        $order = isset($_POST['iSortCol_0']) ? trim($_POST['iSortCol_0']) : 'id';

        switch ($order) {
            default:
                $order = 'u.nome';
                break;
        }

        $criteria->order = $order; //. ' ' . $sort;
        $criteria->limit = $rows;
        $criteria->offset = $offset;


        $model = TbTipodocumento::model()->findAll($criteria);
        $grid = array();
        $i = 0;

        foreach ($model as $m) {
            
            $grid[0] = '<input id="'. $m->id . '" type="checkbox" onclick="MarcarTdoc(' . $m->id . ')" href="javascript:void(0)">';
            $grid[1] = $m->nome;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }
}
