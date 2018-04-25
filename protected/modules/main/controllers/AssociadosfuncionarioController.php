<?php

class AssociadosfuncionarioController extends Controller {
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
     * data criação: 23/11/2015
     * data última atualização: 23/11/2015 
     * descrição: 
     *      renderiza o o formulario "main/associadosfuncionario/index.php"
     */

    public function actionIndex() {
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 23/11/2015
     * data última atualização: 06/06/2016 
     * descrição: 
     *      Verifica se o Usuário/Funcionário existe, se esta ativo e não deletado na base de dados.
     */

    public function verificarExistenciaFuncionario($usuario, $codigo) {

        $criteria = new CDbCriteria();

        if ($codigo == 0) {
            $criteria->condition = "usuario=:usuario and status!=:status";
            $criteria->params = array(":usuario" => $usuario, ":status" => "E");
        } else {
            $criteria->condition = "usuario=:usuario and id<>:id and status!=:status";
            $criteria->params = array(":usuario" => $usuario, ":id" => $codigo, ":status" => "E");
        }

        return TbUsuario::model()->count($criteria);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 23/11/2015
     * data última atualização: 31/05/2016 
     * descrição: 
     *      
     */

    public function actionCreate($ce = "") {

        if (empty($ce) && !is_numeric($ce)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
        }

        if (!$this->verificaExisteAssociado($ce)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
        }

        $retorno = array();

        $associado = TbAssociado::model()->findByPk($ce);

        if (!is_null($associado)) {

            $model = new TbAssociadoFuncionario();

            if ($_POST) {

                $retorno['tipo'] = "SUCESSO";
                $retorno['msg'] = "create";

                $connection = Yii::app()->db;
                $transaction = $connection->beginTransaction();

                try {

                    //variável referente ao usuário
                    $usuario = trim($_POST['usuario']);
                    if (empty($usuario)) {
                        throw new Exception("<strong>Usuário</strong> não pode ser vazio.");
                    } else {
                        //restruturar o nome no usuário
                        $usuario = Util::replaceCaracterEspecial(Util::spaceToPoint($usuario));
                    }
                    //variável referente a senha
                    $senha = trim($_POST['senha']);
                    if (empty($senha)) {
                        $senha = Util::geraSenha();
                    }
                    //variável referente a nome
                    $nome = trim($_POST['nome']);
                    if (empty($nome)) {
                        throw new Exception("<strong>Nome</strong> não pode ser vazio.");
                    }
                    //variável referente ao nivel de acesso
                    $nivel = trim($_POST['nivel']);
                    if (empty($nivel)) {
                        throw new Exception("Por favor Selecione um <strong>Nível de acesso.</strong>");
                    }
                    //variável referente a email
                    $email = trim($_POST['email']);
                    if (empty($email)) {
                        throw new Exception("<strong>E-mail</strong> não pode ser vazio.");
                    }

                    $retorno['mail'] = $email;
                    $keyCode = substr(SHA1(uniqid(rand(), true)), 0, 30);

                    $model->nome = Util::toUpperSpecial($nome);
                    $model->email = $email;
                    $model->identidade = Yii::app()->user->identidade;
                    $model->idassociado = $ce;
                    $model->status = "I";
                    $model->operador = Yii::app()->user->id;

                    if ($model->validate()) {

                        if ($this->verificarExistenciaFuncionario($usuario, 0) > 0) {
                            throw new Exception("Usuário já cadastrado");
                        }
                        if (!$model->save()) {
                            throw new Exception("Falha ao tentar salvar");
                        }
                        //Cadastrar Usuário/Funcionário
                        if (!$this->cadastrarUsuarioFuncionario($model->nome, $model->email, $usuario, $senha, $model->identidade, $ce, $nivel, $model->id, $keyCode)) {
                            throw new Exception("Falha ao cadastrar funcionário/usuário.");
                        }

                        $parametros["email"] = $email;
                        $parametros["assunto"] = "DocFree - Ativação de conta (Funcionário)";
                        $parametros["mensagem"] = "<a href='" . Yii::app()->createAbsoluteUrl('/main/associadosfuncionario/ativarconta/t/' . $keyCode) . "'>" . Yii::app()->createAbsoluteUrl('/main/usuarios/validarconta/t/' . $keyCode) . "</a>";

                        if (Email::enviarEmail($parametros)) {

                            $retorno['tipo'] = "SUCESSO";
                            $retorno['msg'] = "create";
                        } else {
                            throw new Exception('<strong>Nada foi feito</strong>, Falha ao enviar e-mail de redefinição de senha');
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
            }
        }
        $this->render('create', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 23/11/2015
     * data última atualização: 23/11/2015 
     * descrição: 
     *      Verifica se o token esta cadastrado na base de dados, se True atualiza a Coluna (tokenusuario = "")
     *      (ativo = S).
     */

    public function actionAtivarconta() {
        $keyCode = $_GET["t"];
        $criteria = new CDbCriteria;
        $criteria->condition = "tokenusuario=:token";
        $criteria->params = array(":token" => $keyCode);
        $total = Usuarios::model()->count($criteria);

        if ($total > 0) {
            $model = Usuarios::model()->find($criteria);
            $model->tokenusuario = "";
            $model->ativo = "S";
            $model->save();
        } else {
            $this->redirect(array("/login/index"));
        }

        $this->redirect(array("/login/default/index/a/ok"));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlauroouza
     * data criação: 24/11/2015
     * data última atualização: 16/12/2015 
     * descrição: 
     *      
     */

    public function actionUpdate($ce = "", $id = "", $msg = "") {

        if (empty($ce) && empty($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
        }

        if (!is_numeric($ce) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
        }

        if (!$this->verificaExisteAssociado($ce)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/index'));
        }

        if (!$this->verificarUsuarioAtivo($id)) {
            if (!empty($msg)) {
                $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/create/ce/' . $ce . '/msg/' . $msg));
            } else {
                $this->redirect(Yii::app()->createAbsoluteUrl('main/associadosfuncionario/create/ce/' . $ce));
            }
        }

        $retorno = array();

        $model = $this->loadModel($id);

        if ($_POST) {
            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "update";
            try {

                //variável referente a senha
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    throw new Exception("<strong>Nome</strong> não pode ser vazio.");
                }

                //variável referente a email
                $email = trim($_POST['email']);
                if (empty($email)) {
                    throw new Exception("<strong>E-mail</strong> não pode ser vazio.");
                }

                $model->nome = Util::toUpperSpecial($nome);
                $model->email = trim($email);

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
     * data criação: 24/11/2015
     * data última atualização: 30/05/2016 
     * descrição: 
     *      
     */

    public function loadModel($id) {
        $model = TbAssociadoFuncionario::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'O requerimento não existe.');
        return $model;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 24/11/2015
     * data última atualização: 25/11/2015 
     * descrição: 
     *      Verifica se o Usuario esta ativo e não deletado.
     */

    private function verificarUsuarioAtivo($id) {

        if (!is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();
        $criteria->condition = "id=:id and status=:status";
        $criteria->params = array(":id" => $id, ":status" => "A");

        $total = TbAssociadoFuncionario::model()->count($criteria);

        if ($total > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/12/2015
     * data última atualização: 06/11/2017 
     * descrição: 
     *      
     */

    public function actionGrid() {

        $condition = '';
        $params = array();

        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->alias = "f";
        $criteria->select = "f.*";

        $condition .= "f.status=:status"; // AND f.idassociado=:idassociado";
        $params[":status"] = "A";

        $criteria->condition = $condition;
        $criteria->params = $params;

        $result["iTotalRecords"] = TbAssociadoFuncionario::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbAssociadoFuncionario::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;

        $sort = isset($_POST['sSortDir_0']) ? trim($_POST['sSortDir_0']) : 'ASC';
        $order = isset($_POST['iSortCol_0']) ? trim($_POST['iSortCol_0']) : 'id';

        switch ($order) {
            case 0:
                // CÓD.
                $order = 'f.id';
                break;
            default:
                $order = 'f.idassociado';
                break;
        }

        $criteria->order = $order . ' ' . $sort;
        $criteria->limit = $rows;
        $criteria->offset = $offset;

        $model = TbAssociadoFuncionario::model()->findAll($criteria);

        $grid = array();
        $i = 0;

        foreach ($model as $m) {

            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associadosfuncionario/update/ce/' . $m->idassociado . '/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->idassociado0['nomerazao'];
            $grid[2] = $m->nome;
            $grid[3] = $m->email;
            $grid[4] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza 
     * data criação: 07/12/2015
     * data última atualização: 09/12/2015 
     * descrição: 
     *      
     */

    public function actionGridfuncionarios($ce) {

        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->condition = "idassociado=:id and status=:status";
        $criteria->params = array(":id" => $ce, ":status" => "A");

        $result["iTotalRecords"] = TbAssociadoFuncionario::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbAssociadoFuncionario::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;



        $criteria->order = 'id desc';
        $criteria->limit = $rows;
        $criteria->offset = $offset;

        $model = TbAssociadoFuncionario::model()->findAll($criteria);

        $grid = array();
        $i = 0;

        foreach ($model as $m) {

            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associadosfuncionario/update/ce/' . $m->idassociado . '/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->nome;
            $grid[2] = $m->email;
            $grid[3] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 16/12/2015
     * data última atualização: 31/05/2016 
     * descrição: 
     *      Verifica se a Empresa Associada existe, e se o status esta Avito na base de dados.
     */

    private function verificaExisteAssociado($id) {

        if (!is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "id=:id AND status=:status";
        $criteria->params = array(":id" => $id, ":status" => "A");

        $total = TbAssociado::model()->count($criteria);

        if ($total == 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 06/06/2016
     * data última atualização: 06/06/2016 
     * descrição: 
     *      Cadastra um novo Usuário referênte ao novo funcionário.
     *      @Parametro - return = 'True': significa que o cadastro foi bem sucedido.
     *      @Parametro - return = 'False': significa que o cadastro não foi bem sucedido.
     */

    private function cadastrarUsuarioFuncionario($nome, $email, $usuario, $senha, $identidade, $ce, $nivel, $id, $keyCode) {

        if (isset($id) && !is_numeric($id)) {
            return false;
        }

        $modelUser = new TbUsuario();

        $modelUser->nome = Util::toUpperSpecial($nome);
        $modelUser->email = $email;
        $modelUser->usuario = $usuario;
        $modelUser->senha = SHA1($senha);
        $modelUser->identidade = $identidade;
        $modelUser->idassociado = $ce;
        $modelUser->keycode = $keyCode;
        $modelUser->idnivel = $nivel;
        $modelUser->datacadastro = date("Y-m-d H:i:s");
        $modelUser->status = "I";
        $modelUser->tipousuario = "A";
        $modelUser->idfuncionario = $id;
        $modelUser->operador = Yii::app()->user->id;

        if (!$modelUser->validate()) {
            return false;
        } else {
            if ($modelUser->save()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 07/11/2017
     * data última atualização: 07/11/2017
     * descrição: 
     *      Atualiza as Colunas (status = I) , inválidando o funcionário associado.
     * Tabela: TbAssociadoFuncionario
     */

    public function actionInactivate($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/assinaturas/index'));
        }

        $model = TbAssociadoFuncionario::model()->findByPk($id);

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "ok";

        try {

            $model->status = "I";
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

}

