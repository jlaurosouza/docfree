<?php

class DepartamentoController extends Controller {
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
     * data criação: 06/06/2016
     * data última atualização: 06/06/2016 
     * descrição: 
     *      Direciona para o _Form se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo departamento a base de dados.
     *      
     */

    public function actionCreate() {

        $model = new TbDepartamento();
        $retorno = array();

        if ($_POST) {
            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";

            try {

                //variável referente ao usuário
                $departamento = trim($_POST['departamento']);
                if (empty($departamento)) {
                    throw new Exception("<strong>Departamento</strong> não pode ser vazio.");
                }

                $model->departamento = $departamento;
                $model->identidade = Yii::app()->user->identidade;
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExistenciaDepartamento($departamento, 0) > 0) {
                        throw new Exception("Departamento já cadastrado");
                    }
                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    }

                    $retorno['tipo'] = "SUCESSO";
                    $retorno['msg'] = "ok";
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $retorno['tipo'] = "error";
                $retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($retorno));
        }
        $this->render('create', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 08/11/2017
     * data última atualização: 08/11/2017 
     * descrição: 
     *      Atualiza os Dados do departamento
     */

    public function actionUpdate($id = '') {
        
        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/departamento/index'));
        }

        $model = $this->loadModel($id);

        $retorno = array();

        if ($_POST) {

            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";

            try {
                //variável referente ao departamento
                $departamento = trim(Util::toUpperSpecial($_POST['departamento']));
                if (empty($departamento)) {
                    throw new Exception("<strong>o Departamento</strong> não pode ser vazio.");
                }

                if ($this->verificarExistenciaDepartamento($departamento, $id) > 0) {
                    throw new Exception("Departamento já cadastrado");
                }
               
                $model->departamento = Util::toUpperSpecial($departamento);
                $model->operador = Yii::app()->user->id;
               
                if ($model->validate()) {
                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    }
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $retorno['tipo'] = "error";
                $retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($retorno));
        }
        $this->render('update', array('model' => $model,));
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
        $model = TbDepartamento::model()->findByPk($id);
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

        $condition .= "status=:status";
        $params[":status"] = "A";

        $criteria->condition = $condition;
        $criteria->params = $params;

        $result["iTotalRecords"] = TbDepartamento::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbDepartamento::model()->count($criteria);
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


        $model = TbDepartamento::model()->findAll($criteria);
        $grid = array();
        $i = 0;

        foreach ($model as $m) {

            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/departamento/update/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->departamento;
            $grid[2] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 06/06/2016
     * data última atualização: 06/06/2016 
     * descrição: 
     *      Verifica se o departamento já existe na base de dados.
     */

    private function verificarExistenciaDepartamento($Departamento, $codigo) {

        $criteria = new CDbCriteria();

        if ($codigo == 0) {
            $criteria->condition = "departamento=:departamento AND status=:status";
            $criteria->params = array(":departamento" => $Departamento, ":status" => 'A');
        } else {
            $criteria->condition = "departamento=:departamento and id<>:id AND status=:status";
            $criteria->params = array(":departamento" => $Departamento, ":id" => $codigo, ":status" => 'A');
        }

        return TbDepartamento::model()->count($criteria);
    }

    /*
     * autor: Lauro Souza
     * atualizado por: 
     * data criação: 08/11/2017
     * data última atualização: 08/11/2017 
     * descrição: 
     *      Atualiza as Colunas (status = I), inválidando o departamento.
     * Tabela: TbDepartamento
     */

    public function actionInactivate($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/departamento/index'));
        }

        $model = TbDepartamento::model()->findByPk($id);

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "ok";

        try {

            if ($this->verificarDepartamentoVinculado($id)) {
                throw new Exception('<strong>Impossível inativar</strong>, existem informações vinculadas ao departamento!');
            }

            $model->status = "I";

            if ($model->validate()) {
                if (!$model->save()) {
                    throw new Exception("Falha ao tentar salvar");
                }
            } else {
                throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
            }
        } catch (Exception $ex) {
            $retorno['tipo'] = "error";
            $retorno['msg'] = $ex->getMessage();
        }
        Yii::app()->end(json_encode($retorno));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 08/11/2017
     * data última atualização: 08/11/2017 
     * descrição: 
     *      
     */

    private function verificarDepartamentoVinculado($id = '') {

        if (isset($id) && !is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "iddepartamento=:iddepartamento";
        $criteria->params = array(":iddepartamento" => $id);

        $total = TbDepartamentoTipodocumento::model()->count($criteria);

        if ($total > 0) {
            return true;
        } else {
            return false;
        }
    }

}