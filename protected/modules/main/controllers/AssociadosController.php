<?php

spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once(Yii::app()->basePath . '/components/Canvas.php');
spl_autoload_register(array('YiiBase', 'autoload'));

class AssociadosController extends Controller {
    /* === DECLARAÇÕES DAS VARIAVÉIS === */

    // Variavéis Referênte ao Upload da logo Marca
    public $caminhoImg;
    public $extensoesImg;
    public $caminhoImgMini;

    /* === Variável responsável a receber a senha aleatória do usuário (quando necessário) === */
    public $senhaAleatoria;
    public $retorno = array();

    /* === FIM DA DECLARAÇÕES DE VARIÁVEIS === */

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
        $this->caminhoImg = Yii::app()->basePath . "/../images/logomarca/uploads/associados/";
        $this->caminhoImgMini = Yii::app()->basePath . "/../images/logomarca/uploads/associados/mini/";
        $this->extensoesImg = array("png", "PNG", "gif", "GIF", "jpg", "JPG", "jpeg", "JPEG");
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 26/11/2015
     * data última atualização: 26/11/2015 
     * descrição: 
     *     
     */

    public function verificarAssociadoAtivo($id, $tipo) {

        if (!is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "id=:id and tipo=:tipo and status=:status";
        $criteria->params = array(":id" => $id, ":tipo" => $tipo, ":status" => "A");

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
     * data criação: 20/11/2015
     * data última atualização: 20/11/2015 
     * descrição: 
     *      renderiza o o formulario "main/associados/index.php"
     */

    public function actionLogomarca($ce = '') {

        $model = TbAssociado::model()->findByPk($ce);

        if ($_FILES) {
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {
                //verifica se o Arquivo existe
                if (empty($_FILES['file']["name"])) {
                    throw new Exception("<strong>Logomarca</strong> não pode ser vazio. <p> Selecione uma imagem.");
                } else {

                    # Verifica se a pasta "MINI" esta criada
                    if (!is_dir($this->caminhoImgMini)) {
                        mkdir($this->caminhoImgMini, 0755);
                    }

                    $nomeImagem = $model->id . "_" . Yii::app()->user->id . "_" . md5(uniqid(rand(), true));
                    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

                    /* variavel recebe nome original da imagem com extensão */
                    $novoNome = $nomeImagem . "." . $ext;
                                        
                    if($_FILES['file']['tmp_name'] == ""){
                        throw new Exception("Falha ao tentar realizar upload do arquivo - Erro não indentificado.");
                    }
                    
                    $sizeImg = getimagesize($_FILES['file']['tmp_name']);
                    // largura	
                    $size_w = $sizeImg[0];
                    // altura
                    $size_h = $sizeImg[1];


                    $upload = new Upload($_FILES['file'], $size_w, $size_h, $this->caminhoImg, $nomeImagem);

                    $salvo = $upload->salvar();

                    if ($salvo == "true") {
                        
                        $targetFile = $this->caminhoImg . $novoNome;
                        $targetCustomFile = $this->caminhoImgMini . $novoNome;

                        # Instancia um objeto canvas
                        $objCanvas = new Canvas();

                        $objCanvas->carrega($targetFile)->hexa('#FFFFFF')->redimensiona(100, 80, 'preenchimento')->grava($targetCustomFile);
                    } else {
                        throw new Exception("Falha ao tentar realizar upload do arquivo. - Erro: " . $salvo);
                    }
                   
                    if (!$this->salvarLogoBd($nomeImagem . "." . $ext, $model->id, "")) {
                        throw new Exception("Falha ao tentar salvar imagem");
                    }
                }
                $transaction->commit();
                $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/logomarca/ce/' . $ce));
            } catch (Exception $ex) {
                $transaction->rollBack();
                $model->addError('', $ex->getMessage());
            }
        }
        $this->render('logoMarca', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 16/06/2016
     * data última atualização: 16/06/2016 
     * descrição: 
     *      renderiza o o formulario "main/associados/index.php"
     */

    public function actionIndex() {
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 20/11/2015
     * data última atualização: 29/05/2016 
     * descrição: 
     *      Direciona para o _FormPF se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo associado do tipo físico a base de dados.
     */

    public function actionCreatepf() {

        $model = new TbAssociado();

        if ($_POST) {

            $this->retorno['tipo'] = "SUCESSO";
            $this->retorno['msg'] = "ok";

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {

                //Variável recebe senha aleatória.
                $this->senhaAleatoria = Util::geraSenha();

                //Variável referente ao nome.
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    throw new Exception("<strong>Nome</strong> não pode ser vazio.");
                }

                //Variável referente ao CPF.
                $documento = Util::removerMaskCPF($_POST['documento']);
                if (empty($documento)) {
                    throw new Exception("<strong>CPF</strong> não pode ser vazio.");
                }

                //Variável referente ao e-mail.
                $email = trim($_POST['email']);
                if (empty($email)) {
                    throw new Exception("<strong>E-mail</strong> não pode ser vazio.");
                }

                $model->nomerazao = Util::toUpperSpecial($nome);
                $model->nomefantasia = Util::toUpperSpecial($nome);
                $model->tipo = 'F';
                $model->documento = $documento;
                $model->home = $_POST['home'];
                $model->email = $email;
                $model->responsavel = Util::toUpperSpecial($nome);
                $model->emailresponsavel = $email;
                $model->cep = trim($_POST['cep']);
                $model->idestado = $_POST['estado'];
                $model->idcidade = $_POST['cidade'];
                $model->bairro = Util::toUpperSpecial(trim($_POST['bairro']));
                $model->logradouro = Util::toUpperSpecial(trim($_POST['logradouro']));
                $model->numero = Util::toUpperSpecial(trim($_POST['numero']));
                $model->complemento = Util::toUpperSpecial(trim($_POST['complemento']));
                $model->datacadastro = date("Y-m-d H:i:s");
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExistenciaCliente($model->documento, 0) > 0) {
                        throw new Exception("<strong>CPF</strong> já cadastrado");
                    }

                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    } else {
                        //Cadastrando telefones
                        if (!empty($_POST['fones'])) {
                            if (!Util::inserirTelefone("associados", $model->id, $_POST['fones'], $_POST['operadoras'])) {
                                throw new Exception("<strong>Nada foi feito</strong>, Falha ao tentar cadastra telefones");
                            }
                        }

                        //fim do cadastro
                        if (!$this->criarUsuarioAdmin($model->id, $nome, $email)) {
                            throw new Exception("<strong>Nada foi feito</strong>, Falha ao criar usuário Administrador");
                        }
                    }
                    $transaction->commit();
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $this->retorno['tipo'] = "error";
                $this->retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($this->retorno));
        }
        $this->render('createpf', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 23/11/2015
     * data última atualização: 16/12/2015 
     * descrição: 
     *      
     */

    public function actionUpdatepf($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
        }

        if (!$this->verificarAssociadoAtivo($id, "F")) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
        }

        $model = $this->loadModel($id, "F");

        if ($_POST) {

            $this->retorno['tipo'] = "SUCESSO";
            $this->retorno['msg'] = "ok";

            try {

                //Variável referente ao nome
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    throw new Exception('<strong>Nome</strong> não pode ser vazio.');
                }

                //Variável referente ao CPF
                $documento = Util::removerMaskCPF($_POST['documento']);
                if (empty($documento)) {
                    throw new Exception('<strong>CPF</strong> não pode ser vazio.');
                }

                //Variável referente ao e-mail
                $email = trim($_POST['email']);
                if (empty($email)) {
                    throw new Exception('<strong>E-mail</strong> não pode ser vazio.');
                }

                $model->nomerazao = Util::toUpperSpecial($nome);
                $model->nomefantasia = Util::toUpperSpecial($nome);
                $model->tipo = 'F';
                $model->documento = $documento;
                $model->home = $_POST['home'];
                $model->email = $email;
                $model->responsavel = Util::toUpperSpecial($nome);
                $model->emailresponsavel = $email;
                $model->cep = trim($_POST['cep']);
                $model->idestado = $_POST['estado'];
                $model->idcidade = $_POST['cidade'];
                $model->bairro = Util::toUpperSpecial(trim($_POST['bairro']));
                $model->logradouro = Util::toUpperSpecial(trim($_POST['logradouro']));
                $model->numero = Util::toUpperSpecial(trim($_POST['numero']));
                $model->complemento = Util::toUpperSpecial(trim($_POST['complemento']));
                $model->datacadastro = date("Y-m-d H:i:s");
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {
                    if ($this->verificarExistenciaCliente($model->documento, $id) > 0) {
                        throw new Exception("<strong>, CPF</strong> cadastrado para outro associado");
                    }
                    if (!$model->save()) {
                        throw new Exception('Desculpe-nos... Ocorreu algo inesperado. Não conseguimos atualizar o associados.');
                    } else {
                        //Cadastrando telefones
                        if (!empty($_POST['fones'])) {
                            if (!Util::inserirTelefone("associados", $model->id, $_POST['fones'], $_POST['operadoras'])) {
                                throw new Exception("<strong>Nada foi feito</strong>, Falha ao tentar cadastra telefones");
                            }
                        }
                        //fim do cadastro
                    }
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $this->retorno['tipo'] = "error";
                $this->retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($this->retorno));
        }

        $this->render('updatepf', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 20/11/2015
     * data última atualização: 29/05/2016 
     * descrição: 
     *      Direciona para o _FormPF se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo associado é do tipo jurídico a base de dados.
     */

    public function actionCreatepj() {

        $model = new TbAssociado();

        if ($_POST) {

            $this->retorno['tipo'] = "SUCESSO";
            $this->retorno['msg'] = "ok";

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {

                //Variável recebe senha aleatória.
                $this->senhaAleatoria = Util::geraSenha();

                //Variável referente a Razão social
                $razao = trim($_POST['razao']);
                if (empty($razao)) {
                    throw new Exception("<strong>Razão Social</strong> não pode ser vazio.");
                }

                //Variável referente ao CNPJ
                $documento = Util::removerMaskCNPJ($_POST['documento']);
                if (empty($documento)) {
                    throw new Exception("<strong>CNPJ</strong> não pode ser vazio.");
                }

                //Variável referente ao e-mail do responsável
                $respEmail = trim($_POST['emailresponsavel']);
                if (empty($respEmail)) {
                    throw new Exception("<strong>E-mail do Responsável</strong> não pode ser vazio.");
                }

                //Variável referente ao responsável
                $responsavel = trim($_POST['responsavel']);
                if (empty($responsavel)) {
                    throw new Exception("<strong>Nome do Responsável</strong> não pode ser vazio.");
                }

                $model->nomerazao = Util::toUpperSpecial($razao);
                $model->nomefantasia = Util::toUpperSpecial($razao);
                $model->tipo = 'J';
                $model->documento = $documento;
                $model->home = $_POST['home'];
                $model->email = $_POST['email'];
                $model->responsavel = Util::toUpperSpecial($responsavel);
                $model->emailresponsavel = $respEmail;
                $model->cep = trim($_POST['cep']);
                $model->idestado = $_POST['estado'];
                $model->idcidade = $_POST['cidade'];
                $model->bairro = Util::toUpperSpecial(trim($_POST['bairro']));
                $model->logradouro = Util::toUpperSpecial(trim($_POST['logradouro']));
                $model->numero = Util::toUpperSpecial(trim($_POST['numero']));
                $model->complemento = Util::toUpperSpecial(trim($_POST['complemento']));
                $model->datacadastro = date("Y-m-d H:i:s");
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {

                    if ($this->verificarExistenciaCliente($model->documento, 0) > 0) {
                        throw new Exception("Cliente já cadastrado");
                    }
                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
                    } else {

                        //Cadastrando telefones
                        if (!empty($_POST['fones'])) {
                            if (!Util::inserirTelefone("associados", $model->id, $_POST['fones'], $_POST['operadoras'])) {
                                throw new Exception("<strong>Nada foi feito</strong>, Falha ao tentar cadastra telefones");
                            }
                        }
                        //fim do cadastro

                        if (!$this->criarUsuarioAdmin($model->id, $model->responsavel, $model->emailresponsavel)) {
                            throw new Exception("<strong>Nada foi feito</strong>, Falha ao criar usuário Administrador");
                        }
                    }
                    $transaction->commit();
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
                $this->retorno['tipo'] = "error";
                $this->retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($this->retorno));
        }
        $this->render('createpj', array('model' => $model,));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 23/11/2015
     * data última atualização: 16/12/2015 
     * descrição: 
     *      
     */

    public function actionUpdatepj($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
        }

        if (!$this->verificarAssociadoAtivo($id, "J")) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
        }

        $model = $this->loadModel($id, "J");

        if ($_POST) {

            $this->retorno['tipo'] = "SUCESSO";
            $this->retorno['msg'] = "ok";
            try {
                //Variável referente a Razão Social
                $razao = trim($_POST['razao']);
                if (empty($razao)) {
                    throw new Exception("<strong>Razão Social</strong> não pode ser vazio.");
                }

                //Variável referente ao CNPJ
                $documento = Util::removerMaskCNPJ($_POST['documento']);
                if (empty($documento)) {
                    throw new Exception("<strong>CNPJ</strong> não pode ser vazio.");
                }

                //Variável referente ao e-mail do responsável
                $respEmail = trim($_POST['emailresponsavel']);
                if (empty($respEmail)) {
                    throw new Exception("<strong>E-mail do Responsável</strong> não pode ser vazio.");
                }

                //Variável referente ao responsável
                $responsavel = trim($_POST['responsavel']);
                if (empty($responsavel)) {
                    throw new Exception("<strong>Nome do Responsável</strong> não pode ser vazio.");
                }

                $model->nomerazao = Util::toUpperSpecial($razao);
                $model->nomefantasia = Util::toUpperSpecial($razao);
                $model->tipo = 'J';
                $model->documento = $documento;
                $model->home = $_POST['home'];
                $model->email = $_POST['email'];
                $model->responsavel = Util::toUpperSpecial($responsavel);
                $model->emailresponsavel = $respEmail;
                $model->cep = trim($_POST['cep']);
                $model->idestado = $_POST['estado'];
                $model->idcidade = $_POST['cidade'];
                $model->bairro = Util::toUpperSpecial(trim($_POST['bairro']));
                $model->logradouro = Util::toUpperSpecial(trim($_POST['logradouro']));
                $model->numero = Util::toUpperSpecial(trim($_POST['numero']));
                $model->complemento = Util::toUpperSpecial(trim($_POST['complemento']));
                $model->datacadastro = date("Y-m-d H:i:s");
                $model->operador = Yii::app()->user->id;

                if ($model->validate()) {
                    if ($this->verificarExistenciaCliente($model->documento, $id) > 0) {
                        throw new Exception("<strong>CNPJ</strong> cadastrado para outro associado");
                    }
                    if (!$model->save()) {
                        throw new Exception('Desculpe-nos... Ocorreu algo inesperado. Não conseguimos atualizar a associados.');
                    } else {
                        //Cadastrando telefones
                        if (!empty($_POST['fones'])) {
                            if (!Util::inserirTelefone("associados", $model->id, $_POST['fones'], $_POST['operadoras'])) {
                                throw new Exception("<strong>Nada foi feito</strong>, Falha ao tentar cadastra telefones");
                            }
                        }
                        //fim do cadastro
                    }
                } else {
                    throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
                }
            } catch (Exception $ex) {
                $this->retorno['tipo'] = "error";
                $this->retorno['msg'] = $ex->getMessage();
            }
            Yii::app()->end(json_encode($this->retorno));
        }

        $this->render('updatepj', array(
            'model' => $model,
        ));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 20/11/2015
     * data última atualização: 29/05/2016 
     * descrição: 
     *      Verifica se o associado existe, se o status esta ativo na base de dados.
     */

    public function verificarExistenciaCliente($documento, $codigo) {

        $criteria = new CDbCriteria();

        if ($codigo == 0) {
            $criteria->condition = "documento=:documento and status!=:status";
            $criteria->params = array(":documento" => $documento, ":status" => 'E');
        } else {
            $criteria->condition = "documento=:documento and id<>:id and status!=:status";
            $criteria->params = array(":documento" => $documento, ":id" => $codigo, ":status" => 'E');
        }
        return TbAssociado::model()->count($criteria);
        ;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 24/11/2015
     * data última atualização: 29/05/2015 
     * descrição: 
     *      
     */

    public function verificarExistenciaUsuario($usuario, $id) {

        $criteria = new CDbCriteria();

        if ($id == 0) {
            $criteria->condition = "usuario=:usuario and status=:status";
            $criteria->params = array(":usuario" => $usuario, ":status" => "A");
        } else {
            $criteria->condition = "usuario=:usuario and id<>:id and status=:status";
            $criteria->params = array(":usuario" => $usuario, ":id" => $id, ":status" => "N");
        }
        return TbUsuario::model()->count($criteria);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 23/11/2015
     * data última atualização: 29/05/2016 
     * descrição: 
     *      Carrega o formuário com os dados do associado.
     *      Verifica se os Dados são Validos, $id = id do associado, $tipo = tipo do associado (F ou J), o tipo serve para
     *      não ser carregado um associado Físico no formulário do Jurídico ou um Jurídico no formulário do físico.
     */

    public function loadModel($id, $tipo) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id=:id and tipo=:tipo and status=:status";
        $criteria->params = array(":id" => $id, ":tipo" => $tipo, ":status" => "A");

        $model = TbAssociado::model()->find($criteria);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 23/11/2015
     * data última atualização: 21/12/2015 
     * descrição: 
     *      
     */

    public function actionDelete($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/associados/index'));
        }

        $model = TbAssociado::model()->findByPk($id);

        $url = Yii::app()->createAbsoluteUrl('main/associados/index');

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {

            $model->deletado = "S";
            $model->ativo = "N";

            if ($model->validate()) {

                /* Verifica se existe integração do Cliente com eventos */
                if ($this->verificarIntegracaoClientexEvento($id)) {
                    throw new Exception('<strong>Impossível excluir</strong>, existe evento integrado a associados!');
                    $url .= '/msg/vie';
                }

                if (!$model->save()) {
                    throw new Exception("Falha ao tentar salvar");
                } else {

                    /* Deleta os funcionários, mais antes verifica se existe ingressos com eventos 
                     * ativos vendidos pelo funcionário, se existir não poderá excluír. */
                    if (!$this->deletarfuncionario($id, Yii::app()->user->produtora)) {
                        throw new Exception('<strong>Nada foi feito</strong>, Falha ao deletar funcionários');
                        $url .= '/msg/def';
                    }
                }
                $transaction->commit();
                $this->redirect($url . '/msg/delete');
            } else {
                throw new Exception('<strong>Nada foi feito</strong>, Falha ao validar formulário');
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            $this->redirect($url);
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 25/11/2015
     * data última atualização: 21/12/2015 
     * descrição: 
     *      Deleta os funcionários, mais antes verifica se existe ingressos com eventos 
     *      ativos vendidos pelo funcionário, se existir não poderá excluír.
     */

    private function deletarfuncionario($idEmpresa = "", $idProdutora = "") {

        if (empty($idEmpresa) && is_numeric($idEmpresa) && empty($idProdutora) && is_numeric($idProdutora)) {

            $criteria = new CDbCriteria();
            $criteria->condition = "idprodutorasclientes=:idprodutorasclientes and idprodutora=:idprodutora";
            $criteria->params = array(":idprodutorasclientes" => $idEmpresa, ":idprodutora" => $idProdutora);

            $count = TbAssociadovendedores::model()->count($criteria);

            if ($count <= 0) {
                return true;
            }

            $modelFunc = TbAssociadovendedores::model()->findAll($criteria);

            foreach ($modelFunc as $md) {

                /* ==== Verificar se estiver ingressos válidos pelo Cliente. ==== */
                if (!$this->verificarExisteIngressos($md->id)) {
                    return false;
                }

                $md->deletado = "S";
                $md->ativo = "N";
                $md->tokenvendedor = "";

                if ($md->validate()) {
                    if (!$md->save()) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 24/11/2015
     * data última atualização: 29/05/2016 
     * descrição: 
     *      Cria um Usuário Administrador para a Conta do Associado.
     */

    private function criarUsuarioAdmin($id, $nome, $email) {

        $modelUser = new TbUsuario();

        $i = 0;

        $usuario = Util::replaceCaracterEspecial(Util::spaceToPoint($nome));

        do {
            if ($this->verificarExistenciaUsuario($usuario, 0) > 0) {
                $i++;
                $usuario = $usuario . $i;
            } else {
                $i = 0;
            }
        } while ($i > 0);

        $token = substr(SHA1(uniqid(rand(), true)), 0, 30);

        $modelUser->nome = trim($nome);
        $modelUser->email = $email;
        $modelUser->usuario = $usuario;
        $modelUser->senha = SHA1($this->senhaAleatoria);
        $modelUser->identidade = Yii::app()->user->identidade;
        $modelUser->idassociado = $id;
        $modelUser->keycode = $token;
        $modelUser->status = "I";
        $modelUser->datacadastro = date("Y-m-d H:i:s");
        $modelUser->idnivel = "1";
        $modelUser->autorizador = 'S';
        $modelUser->tipousuario = 'A';
        $modelUser->operador = Yii::app()->user->id;

        $this->retorno['user'] = $usuario;
        $this->retorno['pwd'] = $this->senhaAleatoria;

        if ($modelUser->validate()) {
            if (!$modelUser->save()) {
                throw new Exception("Falha ao tentar salvar");
            } else {
                $parametros["email"] = $email;
                $parametros["assunto"] = "Docfree - Ativação de conta";
                $parametros["mensagem"] = "<a href='" . Yii::app()->createAbsoluteUrl('/main/usuarios/ativarconta/k/' . $token) . "'>" . Yii::app()->createAbsoluteUrl('/main/usuarios/validarconta/k/' . $token) . "</a><a>Seu Usuario: " . $usuario . "</a><a>Seu Senha: " . $this->senhaAleatoria . "</a><a>Por medida de segurança, Redefina sua senha</a>";

                //enviar Usuario e senha por e-mail
                if (Email::enviarEmail($parametros)) {
                    return true;
                } else {
                    return false;
                }
            }
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

        $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
        $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria;
        $criteria->alias = "e";
        $criteria->select = "e.*";

        $condition .= "e.status=:status";
        $params[":status"] = "A";

        $criteria->condition = $condition;
        $criteria->params = $params;

        $result["iTotalRecords"] = TbAssociado::model()->count($criteria);
        $result["iTotalDisplayRecords"] = TbAssociado::model()->count($criteria);
        $result["iDisplayStart"] = $page;
        $result["iDisplayLength"] = $rows;

        $sort = isset($_POST['sSortDir_0']) ? trim($_POST['sSortDir_0']) : 'ASC';
        $order = isset($_POST['iSortCol_0']) ? trim($_POST['iSortCol_0']) : 'id';

        switch ($order) {
            case 0:
                // CÓD.
                $order = 'e.id';
                break;

            case 1:
                // NOME E RAZÃO SOCIAL.
                $order = 'e.nomefantasia, e.nomerazao';
                break;

            case 2:
                // DOCUMENTO.
                $order = 'e.documento';
                break;

            case 3:
                // E-MAIL
                $order = 'e.email';
                break;
            default:
                // acoes
                $order = 'e.id';
                break;
        }

        $criteria->order = $order . ' ' . $sort;
        $criteria->limit = $rows;
        $criteria->offset = $offset;

        $model = TbAssociado::model()->findAll($criteria);
        $grid = array();
        $i = 0;

        foreach ($model as $m) {

            if ($m->tipo == "J") {
                $nome = $m->nomerazao;
                $tipo = 'JURÍDICO';
                $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associados/updatepj/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';
                $documento = Util::mask($m->documento, "##.###.###/####-##");
            } else {
                $nome = $m->nomefantasia;
                $tipo = 'FÍSICO';
                $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associados/updatepf/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a>';
                $documento = Util::mask($m->documento, "###.###.###-##");
            }
            if (empty($m->logomarca)) {
                $logo = '<center><a style="display:floatleft; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associados/logomarca/ce/' . $m->id) . '"><i class="fa fa-picture-o"></i> Adicionar</a></center>';
            } else {
                $logo = '<center><a class="imgLogo" href="' . Yii::app()->createAbsoluteUrl('main/associados/logomarca/ce/' . $m->id) . '">
                        <figure><img src="' . Yii::app()->request->baseUrl . '/images/logomarca/uploads/associados/mini/' . $m->logomarca . '">                                                            
                            <figcaption>
                                <h3 class="txt-color-white">Click Aqui Para Alterar Logomarca.</h3>                                
                            </figcaption>
                        </figure>
                        </a></center>';
                $btnAcoes = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;" class="btn btn-primary" href="' . Yii::app()->createAbsoluteUrl('main/associados/updatepf/id/' . $m->id) . '"><i class="fa fa-edit"></i> Editar</a><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="inativar(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-ban txt-color-red"></i> Inativar</a><a style="font-size:8,5px;">&nbsp;&nbsp;</a> <div>&nbsp;</div><a style="display:floatleft; padding:5px 10px;" id="btnRemover" class="btn btn-default" onclick="removerLogo(' . $m->id . ')" href="javascript:void(0)"><i class="fa fa-trash-o txt-color-red">&nbsp;</i> Remover   &nbsp;Logomarca</a>';
            }

            $grid[0] = $m->id;
            $grid[1] = $nome;
            $grid[2] = $documento;
            $grid[3] = $m->email;
            $grid[4] = $tipo;
            $grid[5] = $logo;
            $grid[6] = $btnAcoes;

            $result["aaData"][$i] = $grid;
            $i++;
        }

        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 21/12/2015
     * data última atualização: 21/12/2015 
     * descrição: 
     *      Verifica se existe 'ingressos'.
     */

    private function verificarExisteIngressos($id) {

        if (empty($id) && !is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        /* ===== Se existem ingressos já vendidos (validovenda == 'S') e que o evento esteja ativo ==== */
        $criteria->condition = "idvalidadovendapor=:idvalidadovendapor AND deletado=:deletado AND ativo=:ativo AND validovenda=:validovenda AND validoacesso=:validoacesso";
        $criteria->params = array(":idvalidadovendapor" => $id, ":deletado" => "N", ":ativo" => 'S', ":validovenda" => 'S', ":validoacesso" => 'N');

        $count = Ingressos::model()->count($criteria);

        if ($count > 0) {

            $modelIng = Ingressos::model()->findAll($criteria);

            foreach ($modelIng as $md) {

                /* ==== Verificar se o Evento referente ao ingresso esta Ativo ==== */
                if ($this->verificarEventosAtivo($md->idevento)) {
                    return false;
                }
            }
        }
        return true;
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 21/12/2015
     * data última atualização: 21/12/2015 
     * descrição: 
     *      
     */

    private function verificarEventosAtivo($id = '') {

        if (isset($id) && !is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "id=:id AND deletado=:deletado AND ativo=:ativo";
        $criteria->params = array(":id" => $id, ":deletado" => "N", ":ativo" => 'S');

        $total = Ingressos::model()->count($criteria);

        if ($total > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 21/12/2015
     * data última atualização: 21/12/2015 
     * descrição: 
     *      
     */

    private function verificarIntegracaoClientexEvento($id = '') {

        if (isset($id) && !is_numeric($id)) {
            return false;
        }

        $criteria = new CDbCriteria();

        $criteria->condition = "idprodutorasclientes=:id AND deletado=:deletado AND status=:status";
        $criteria->params = array(":id" => $id, ":deletado" => "N", ":status" => 'CONTRATADO');

        $total = produtorasclientesxeventos::model()->count($criteria);

        if ($total > 0) {
            return true;
        } else {
            return false;
        }
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

        if ($imgAntiga != "") {
            unlink($this->caminhoImg . $imgAntiga);
        }

        $associado = TbAssociado::model()->findByPk($id);
        $logoAtual = $associado->logomarca;

        $associado->logomarca = $img;
        if ($associado->save()) {
            return true;
            unlink($this->caminhoImg . $img);

            if (!empty($logoAtual) || !is_null($logoAtual)) {
                unlink($this->caminhoImgMini . $logoAtual);
            }
        } else {
            return true;
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 21/06/2016
     * data última atualização: 21/06/2016 
     * descrição: 
     *      
     */

    public function actionRemoverLogo($id) {

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "confirmado";

        $associado = TbAssociado::model()->findByPk($id);
        $logo = $associado->logomarca;

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {

            $associado->logomarca = "";
            if ($associado->save()) {
                unlink($this->caminhoImg . $logo);
                unlink($this->caminhoImgMini . $logo);
                $transaction->commit();
            } else {
                throw new Exception("Não foi possível remover a <strong>Logomarca. </strong> Por favor tente novamente.");
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

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 05/11/2017
     * data última atualização: 05/11/2017
     * descrição: 
     *      Atualiza as Colunas (status = I) , inválidando o associado.
     * Tabela: TbAssociado
     */

    public function actionInactivate($id = "") {

        if (empty($id) && !is_numeric($id)) {
            $this->redirect(Yii::app()->createAbsoluteUrl('main/assinaturas/index'));
        }

        $model = TbAssociado::model()->findByPk($id);

        $retorno = array();

        $retorno['tipo'] = "SUCESSO";
        $retorno['msg'] = "ok";

        try {

//            if ($this->verificarUsuarioIntegrado($id)) {
//                throw new Exception("<strong>Nível de acesso</strong> Não pode ser removido.");
//            }

            $model->status = "I";
            $model->operador = Yii::app()->user->id;
           // $model->datahoraalteracao = date("Y-m-d H:i:s");

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