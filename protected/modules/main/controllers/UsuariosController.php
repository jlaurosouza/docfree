<?php

spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once(Yii::app()->basePath . '/components/Canvas.php');
spl_autoload_register(array('YiiBase', 'autoload'));

class UsuariosController extends Controller {
    /* === DECLARAÇÃO DAS VARIAVÉIS === */

    // Variavéis Referênte ao Upload da logo Marca
    public $caminhoImg;
    public $extensoesImg;
    public $caminhoImgMini;

    /* === FIM DA DECLARAÇÃO DE VARIÁVEIS === */

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

        //Iniciando as variavéis referente a Logo marca
        $this->caminhoImg = Yii::app()->basePath . "/../images/avatar/";
        $this->caminhoImgMini = Yii::app()->basePath . "/../images/avatar/mini/";
        $this->extensoesImg = array("png", "PNG", "gif", "GIF", "jpg", "JPG", "jpeg", "JPEG");
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 22/06/2016
     * data última atualização: 22/26/2016 
     * descrição: 
     *      
     */

    public function actionAplicarAvatar($id = '') {

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "confirmado";

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {

            $handle = new upload($_FILES['fileAvatar']);
            if ($handle->uploaded) {
                $nomeFile = preg_replace('/\.[^.]*$/', '', $_FILES['fileAvatar']['name']);
                $nomeImagem = $handle->file_new_name_body = $id . "_" . $nomeFile; //md5(uniqid(rand(), true));
                $ext = $handle->file_src_name_ext;
                $novoNome = $nomeImagem . "." . $ext;

                $FileAtava = TbUsuario::model()->findByPk($id)->avata;

                if (!empty($FileAtava)) {
                    //print_r($FileAtava);
                    unlink($this->caminhoImg . $FileAtava);
                    unlink($this->caminhoImgMini . $FileAtava);
                    Yii::app()->user->avata = $novoNome;
                }
                //die("aki");                


                $handle->process($this->caminhoImg);

                if ($handle->processed) {

                    $handle->clean();

                    # Verifica se a pasta "MINI" esta criada
                    if (!is_dir($this->caminhoImgMini)) {
                        mkdir($this->caminhoImgMini, 0755);
                    }

                    $targetFile = $this->caminhoImg . $novoNome;
                    $targetCustomFile = $this->caminhoImgMini . $novoNome;

                    # Instancia um objeto canvas
                    $objCanvas = new Canvas();

                    $objCanvas->carrega($targetFile)->hexa('#FFFFFF')->redimensiona(36, 36, 'preenchimento')->grava($targetCustomFile);

                    if (!$this->salvarLogoBd($novoNome, $id, "")) {
                        throw new Exception("Falha ao tentar salvar avatar");
                    }
                } else {
                    die($handle->error);
                    switch ($handle->error) {
                        case 'Image too short.':
                            $error = "A imagem é muito pequena para ser utilizada. <strong>Dimensão mínima</strong>: 90x95 (pixels)";
                            break;
                        case 'Image too tall.':
                            $error = "A imagem é muito grande para ser utilizada. <strong>Dimensão máxima</strong>: 500x500 (pixels)";
                            break;
                        default :
                            $error = $handle->error;
                            break;
                    }
                    throw new Exception($error);
                }
            } else {
                throw new Exception("Falha ao tentar realizar upload do arquivo.");
            }
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            $retorno['tipo'] = "error";
            $retorno['msg'] = $ex->getMessage();
        }
        Yii::app()->end(json_encode($retorno));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 07/11/2017
     * descrição: 
     *      Verifica se o usuário existe, se esta ativo e não deletado na base de dados.
     */

    public function verificarExistenciaUsuario($usuario, $codigo) {

        $criteria = new CDbCriteria();

        if ($codigo == 0) {
            $criteria->condition = "usuario=:usuario and status<>:status";
            $criteria->params = array(":usuario" => $usuario, ":status" => "E");
        } else {
            $criteria->condition = "id<>:id and status<>:status";
            $criteria->params = array(":usuario" => $usuario, ":id" => $codigo, ":status" => "E");
        }

        return TbUsuario::model()->count($criteria);
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
     * data criação: 17/11/2015
     * data última atualização: 07/11/2017 
     * descrição: 
     *      Direciona para o _Form se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo usuário a base de dados.
     *      Se for informado a validação por email, um token é gerado e um email é enviado para a validação da conta.
     */

    public function actionCreate() {

        $model = new TbUsuario();
        $retorno = array();

        if ($_POST) {

            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";
            $token = "";

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {
                //variável referente ao usuário
                $usuario = trim($_POST['usuario']);
                if (empty($usuario)) {
                    throw new Exception("<strong>Usuário</strong> não pode ser vazio.");
                }

                //restruturar o nome no usuário
                $usuario = Util::spaceToPoint($usuario);
                $usuario = Util::replaceCaracterEspecial($usuario);

                //variável referente a senha
                $senha = trim($_POST['senha']);
                if (empty($senha)) {
                    throw new Exception("<strong>Senha</strong> não pode ser vazia.");
                }

                //variável referente a nome
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    throw new Exception("<strong>Nome</strong> não pode ser vazio.");
                }

                //variável referente a nível de acesso
                $nivel = trim($_POST['nivel']);
                if (empty($nivel)) {
                    throw new Exception("Por favor Selecione um <strong>Nível de acesso.</strong>");
                }

                //variável referente a email
                $email = trim($_POST['email']);

//                if ($_POST['emailvalida'] == '1') {
                if (empty($email)) {
                    throw new Exception("<strong>E-mail</strong> não pode ser vazio.");
                }
                $token = substr(SHA1(uniqid(rand(), true)), 0, 30);
//                }

                $model->datacadastro = date("Y-m-d H:i:s");
                $model->keycode = $token;
                $model->nome = Util::toUpperSpecial($nome);
                $model->email = $email;
                $model->idnivel = $nivel;
                $model->senha = SHA1($senha);
                $model->usuario = $usuario;
                $model->tipousuario = "E";
                $model->identidade = Yii::app()->user->identidade;
                $model->idassociado = Yii::app()->user->idassociado;
                $model->status = "A";
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExistenciaUsuario($model->usuario, 0) > 0) {
                        throw new Exception("Usuário já cadastrado");
                    }
                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    }
//                    if ($_POST['emailvalida'] == '1') {
//                        $parametros["email"] = $email;
//                        $parametros["assunto"] = "Yngresso - Ativação de conta";
//                        $parametros["mensagem"] = "<a href='" . Yii::app()->createAbsoluteUrl('/main/usuarios/ativarconta/t/' . $token) . "'>" . Yii::app()->createAbsoluteUrl('/main/usuarios/validarconta/t/' . $token) . "</a>";
//
//                        if (Email::enviarEmail($parametros)) {
//
//                            $retorno['tipo'] = "SUCESSO";
//                            $retorno['msg'] = "ok";
//                        } else {
//                            throw new Exception('<strong>Nada foi feito</strong>, Falha ao enviar e-mail de redefinição de senha');
//                        }
//                    }
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
        //die ("não é post");
        $this->render('create', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 17/11/2015
     * data última atualização: 07/11/2017 
     * descrição: 
     *      Atualiza os Dados do Usuário
     */

    public function actionUpdate($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/usuarios/index'));
        }

        $retorno = array();

        if (!$this->verificarUsuarioAtivo($id)) {
            $this->redirect(array("index"));
        }

        $model = $this->loadModel($id);
        if ($_POST) {
            $retorno['tipo'] = "SUCESSO";
            $retorno['msg'] = "ok";
            try {

                //variável referente a nome
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    throw new Exception("<strong>Nome</strong> não pode ser vazio.");
                }

                $email = trim($_POST['email']);
                if (empty($email)) {
                    throw new Exception("<strong>E-mail</strong> não pode ser vazio.");
                }

                //variável referente a nível de acesso
                $nivel = trim($_POST['nivel']);
                if (empty($nivel)) {
                    throw new Exception("Por favor Selecione um <strong>Nível de acesso.</strong>");
                }

                $model->nome = Util::toUpperSpecial($nome);
                $model->email = trim($email);
                $model->idnivel = $nivel;

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
     * autor: Lauro Souza
     * atualizado por: 
     * data criação: 19/11/2015
     * data última atualização: 07/11/2017 
     * descrição: 
     *      Atualiza as Colunas (status = I), inválidando o acesso da conta.
     * Tabela: TbUsuario
     */

    public function actionInactivate($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/usuarios/index'));
        }

        $model = TbUsuario::model()->findByPk($id);

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "ok";
        
        if (!$this->verificarUsuarioAtivo($id)) {
            $this->redirect(array("index"));
        }
        try {
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
     * data criação: 19/11/2015
     * data última atualização: 19/11/2015 
     * descrição: 
     *      Carrega o formuário com os dados do usuário.
     */

    public function loadModel($id) {
        $model = TbUsuario::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Verifica se o token esta cadastrado na base de dados, se True atualiza a Coluna (keycode = "")
     *      (status = A).
     */

    public function actionAtivarconta() {
        $token = $_GET["k"];
        $criteria = new CDbCriteria;
        $criteria->condition = "keycode=:keycode";
        $criteria->params = array(":keycode" => $token);
        $total = TbUsuario::model()->count($criteria);

        if ($total > 0) {
            $model = TbUsuario::model()->find($criteria);
            $model->keycode = "";
            $model->status = "A";
            $model->save();
        } else {
            $this->redirect(array("/login/index"));
        }

        $this->redirect(array("/login/default/index/a/ok"));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Valida o s parametros POST e atualiza a senha na base de dados.
     */

    public function actionAtualizarsenha() {

        $msg = array();
        $listaError = array();

        if ($_POST) {

            $senha = $_POST["senhaatual"];
            $nsenha = $_POST["novasenha"];
            $cnsenha = $_POST["cnovasenha"];

            if (empty($senha)) {
                $addError[] = "<strong>Senha atual</strong> deve ser preenchida";
            }
            if (empty($nsenha)) {
                $addError[] = "<strong>Nova Senha</strong> deve ser preenchida";
            }
            if (empty($cnsenha)) {
                $addError[] = "<strong>Confirme</strong> a nova senha";
            }

            if (!empty($addError)) {
                for ($i = 0; $i < count($addError); $i++) {
                    $listaError.= $addError[$i] . "<br>";
                }
            } else {

                if ($this->verificarSenhaAtual($senha) > 0) {
                    if ($nsenha == $cnsenha) {

                        $model = TbUsuario::model()->findByPk(Yii::app()->user->id);
                        $model->senha = SHA1($nsenha);
                        $model->save();

                        $msg["msg"] = "Senha atualizada com sucesso";
                    } else {
                        $listaError = "Confirmação de senha incorreta";
                    }
                } else {
                    $listaError = "<strong>Senha</strong> atual incorreta";
                }
            }
        }

        $this->render("atualizarsenha", array("listaError" => $listaError, "msg" => $msg));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Verifica se a senha informada esta correta.
     */

    private function verificarSenhaAtual($senha) {

        $criteria = new CDbCriteria;
        $criteria->condition = "senha=:senha";
        $criteria->params = array(":senha" => SHA1($senha));

        return TbUsuario::model()->count($criteria);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 19/11/2015
     * data última atualização: 25/11/2015 
     * descrição: 
     *      Verifica se o Usuario esta ativo e não deletado.
     */

    private function verificarUsuarioAtivo($id) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id=:id and status=:status";
        $criteria->params = array(":id" => $id, ":status" => "A");

        $total = TbUsuario::model()->count($criteria);

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

            $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/usuarios/update/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';

            $grid[0] = $m->id;
            $grid[1] = $m->nome;
            $grid[2] = $m->usuario;
            $grid[3] = $m->idassociado0['nomerazao'];
            $grid[4] = $m->idnivel0['nivel'];
            $grid[5] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 21/06/2016
     * data última atualização: 21/06/2016 
     * descrição: 
     *      
     */

    private function salvarLogoBd($img, $id, $imgAntiga) {

        $Usuario = TbUsuario::model()->findByPk($id);

        $imgAntiga = $Usuario->avata;

        $Usuario->avata = $img;
        if ($Usuario->save()) {
            return true;
        } else {
            return true;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 21/06/2016
     * data última atualização: 29/06/2016 
     * descrição: 
     *      
     */

    public function actionRemoverAvatar() {

        $id = $_POST['id'];

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "confirmado";

        $usuario = TbUsuario::model()->findByPk($id);
        $avata = $usuario->avata;

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {

            $usuario->avata = "";
            if ($usuario->save()) {
                unlink($this->caminhoImg . $avata);
                unlink($this->caminhoImgMini . $avata);
                Yii::app()->user->avata = "";
                $transaction->commit();
            } else {
                throw new Exception("Não foi possível remover o <strong>avatar. </strong> Por favor tente novamente.");
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            $retorno['tipo'] = "error";
            $retorno['msg'] = $ex->getMessage();
            Yii::app()->session['tipo'] = 'error';
            Yii::app()->session['msg'] = $ex->getMessage();
        }
        Yii::app()->end(json_encode($retorno));
    }

}