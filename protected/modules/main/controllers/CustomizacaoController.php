<?php

class CustomizacaoController extends Controller {
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
     *      Se receber um POST verifica e adiciona um novo usuário a base de dados.
     *      Se for informado a validação por email, um token é gerado e um email é enviado para a validação da conta.
     */

    public function actionCreate() {

        $model = new TbCustomizacao();

        if ($_POST) {

            $ce = $_POST['id'];

            if (empty($ce) && !is_numeric($ce)) {
                $this->redirect(Yii::app()->createAbsoluteUrl('main/customizacao/index'));
            }

            if (!$this->verificaExisteTipoDocumental($ce)) {
                $this->redirect(Yii::app()->createAbsoluteUrl('main/customizacao/index'));
            }

            $retorno = array();

            $tipoDoc = TbTipodocumento::model()->findByPk($ce);

            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {

                //variável referente ao título do campo
                $titulocampo = trim($_POST['titulocampo']);
                if (empty($titulocampo)) {
                    throw new Exception("<strong>Título do campo</strong> não pode ser vazio.");
                }

                //variável para vefiricação se o campo será o principal ou não.
                $campoprincipal = trim($_POST['campoprincipal']);
                if ($campoprincipal == 'S') {
                    $nomecampo = 'nomedocumento';
                    $ordem = '0';
                    $grupolista = 'ged_nomedocumento';
                    $tipocampo = 'TEXTO';
                } else {
                    $nomecampo = Util::noSpace(Util::replaceCaracterEspecial($titulocampo));
                    $ordem = ($this->proximaOrdem($ce));
                    $grupolista = 'ged_' . $nomecampo;

                    //variável recebe o tipo do campo selecionado.
                    $tipocampo = trim($_POST['tipocampo']);
                }

                $model->idtipodoc = $ce;
                $model->ordem = $ordem;
                $model->identidade = Yii::app()->user->identidade;
                $model->titulocampo = $titulocampo;
                $model->nomecampo = $nomecampo;
                $model->tipocampo = $tipocampo;
                $model->grupolista = $grupolista;
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExisteCampo($model->nomecampo, $ce) > 0) {
                        throw new Exception("Campo já cadastrado");
                    }
                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    }
                    if ($campoprincipal == 'N') {
                        if ($tipocampo == 'SELECAO') {
                            if (!$this->alterTableSel($tipoDoc->tabelautil, $nomecampo)) {
                                throw new Exception("Falha ao tentar alterar tabela referênte ao tipo documental.");
                            }
                        } else {
                            if (!$this->alterTable($tipoDoc->tabelautil, $nomecampo)) {
                                throw new Exception("Falha ao tentar alterar tabela referênte ao tipo documental.");
                            }
                        }
                    }
                    $transaction->commit();
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $retorno['tipo'] = "error";
                $retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($retorno));
            // }
        }
        $this->render('create', array('model' => $model));
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
        $model = TbCustomizacao::model()->findByPk($id);
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

        $result["iTotalRecords"] = TbUsuario::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbUsuario::model()->count($criteria);
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


        $model = TbUsuario::model()->findAll($criteria);
        $grid = array();
        $i = 0;

        foreach ($model as $m) {

            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/usuarios/update/id/' . $m->id) . '"><i class="cus-application-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn" onclick="excluir(' . $m->id . ')" href="javascript:void(0)"><i class="cus-bin-closed"></i> Excluir</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->nome;
            $grid[2] = $m->usuario;
            $grid[3] = $m->idnivel0['nivel'];
            $grid[4] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Verifica se o tipo documental existe na base de dados.
     */

    private function verificaExisteTipoDocumental($id) {

        if (!is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "id=:id";
        $criteria->params = array(":id" => $id);

        $total = TbTipodocumento::model()->count($criteria);

        if ($total == 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Verifica se o tipo documental existe na base de dados.
     */

    private function verificarExisteCampo($nomecampo, $idtipodoc) {

        if (!is_numeric($idtipodoc)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "idtipodoc=:idtipodoc and nomecampo=:nomecampo";
        $criteria->params = array(":idtipodoc" => $idtipodoc, ":nomecampo" => $nomecampo);

        $total = TbCustomizacao::model()->count($criteria);

        if ($total == 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      Verifica qual a próxima ordem do campo
     */

    private function proximaOrdem($id) {

        if (!is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "idtipodoc=:idtipodoc and ordem!=:ordem";
        $criteria->params = array(":idtipodoc" => $id, ":ordem" => "0");

        $total = TbCustomizacao::model()->count($criteria);

        return $total + 1;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza 
     * data criação: 07/06/2016
     * data última atualização: 07/06/2016 
     * descrição: 
     *      
     */

    public function actionGridcustomizacao($ce) {

        $condition = '';
        $params = array();
        
        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->alias = "e";
        $criteria->select = "e.*";
        
        if (isset($_POST['sSearch_0']) && !empty($_POST['sSearch_0'])) {            
            $condition .= "e.idtipodoc=:idtipodoc";
            $params[":idtipodoc"] = trim($_POST['sSearch_0']);                       
        } else {
            $condition .= "e.idtipodoc=:idtipodoc";
            $params[":idtipodoc"] = $ce;            
        }
            
        $criteria->condition = $condition;
        $criteria->params = $params;                

        $result["iTotalRecords"] = TbCustomizacao::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbCustomizacao::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;



        $criteria->order = 'e.ordem';
        $criteria->limit = $rows;
        $criteria->offset = $offset;

        $model = TbCustomizacao::model()->findAll($criteria);

        $grid = array();
        $i = 0;

        foreach ($model as $m) {

//            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/customizacao/update/ce/' . $m->idtipodoc . '/or/' . $m->ordem) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->idtipodoc . "," . $m->ordem . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = '<center>' . $m->ordem . '</center>';
            $grid[1] = $m->titulocampo;
            $grid[2] = $m->tipocampo;
//            $grid[2] = $btnAcoes;

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
     *      Alterar Campo na Tabela referente ao tipo Documental, podendo adicionar novo campo.
     */

    private function alterTable($table, $nomecampo) {

        Yii::app()->db->createCommand("ALTER TABLE {$table} ADD {$nomecampo} 
                                    VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;"
        )->execute();
        return true;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 17/08/2016
     * data última atualização: 17/08/2016 
     * descrição: 
     *      
     */

    private function alterTableSel($table, $nomecampo) {

        Yii::app()->db->createCommand("ALTER TABLE {$table} ADD {$nomecampo} 
                                    int(11) DEFAULT NULL;"
        )->execute();
        return true;
    }

}