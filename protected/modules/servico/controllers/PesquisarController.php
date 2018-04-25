<?php

spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once(Yii::app()->basePath . '/components/Canvas.php');
spl_autoload_register(array('YiiBase', 'autoload'));

class PesquisarController extends Controller {
    /* === DECLARAÇÃO DAS VARIAVÉIS === */

    // Variavéis Referênte ao Upload da logo Marca
    public $caminhoDoc;
    public $extensoesDoc;

    /* === Variável responsável a receber a senha aleatória do usuário (quando necessário) === */
    public $senhaAleatoria;
    public $retorno = array();

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
        $this->caminhoDoc = Yii::app()->basePath . "/../docPdf/";
        $this->extensoesDoc = array("PDF", "pdf");
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
        Yii::app()->user->table = "";
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 25/11/2015 
     * descrição: 
     *      Direciona para o _Form se não receber parametro POST.
     *      Se receber um POST verifica e adiciona um novo usuário a base de dados.
     *      Se for informado a validação por email, um token é gerado e um email é enviado para a validação da conta.
     */

    public function actionCreate() {

        Yii::app()->user->table = "";

        $model = new TbTipodocumento();

        if ($_POST) {

            //Variável referente ao departamento.
            $departamento = $_POST['ddlDepartamento'];
            if (empty($departamento)) {
                throw new Exception("Selecione um <strong>Departamento.</strong>");
            }

            //Variável referente ao tipo documental.
            $tipodoc = $_POST['ddlTipodoc'];
            if (empty($tipodoc)) {
                throw new Exception("Selecione um <strong>Tipo Documental.</strong>");
            }



            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {

//                $model->attributes = $_POST['Eventos'];
//                $model->nome = Util::toUpperSpecial($nome);
//                $model->bairro = Util::toUpperSpecial($_POST['Eventos']['bairro']);
//                $model->logradouro = Util::toUpperSpecial($_POST['Eventos']['logradouro']);
//                $model->complemento = Util::toUpperSpecial($_POST['Eventos']['complemento']);
//                $model->informacaoadicional = Util::toUpperSpecial($infoAdd);
//                $model->idprodutora = Yii::app()->user->produtora;
//                $model->horaaberturaportao = $horaabertura . ':00';
//                $model->extrapolarpublico = $extrapolar;


                if ($_FILES) {
                    if (!empty($_FILES['inputArquivo']["name"])) {

                        $handle = new upload($_FILES['inputArquivo']);

                        if ($handle->uploaded) {
                            $nomeFile = preg_replace('/\.[^.]*$/', '', $_FILES['inputArquivo']['name']);
                            $nomeImagem = $handle->file_new_name_body = $nomeFile; //md5(uniqid(rand(), true));
                            $ext = $handle->file_src_name_ext;
                            $nomeImagem = Util::replaceCaracterEspecial($nomeImagem);
                            $novoNome = $nomeImagem . "." . $ext;
                            move_uploaded_file($_FILES['inputArquivo']['tmp_name'], $this->caminhoDoc . $novoNome);
                        }
                    } else {
                        throw new Exception("Falha ao tentar realizar upload do arquivo.");
                    }
                }
                $transaction->commit();
                //$this->redirect(Yii::app()->createAbsoluteUrl('main/associados/logomarca/ce/' . $ce));
            } catch (Exception $ex) {
                $transaction->rollBack();
                $model->addError('', $ex->getMessage());
            }
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
     * data criação: 05/11/2016
     * data última atualização: 10/11/2016 
     * descrição: 
     *      
     */

    public function actionGrid() {

        $table = Yii::app()->user->table;

        if (!empty($table)) {
            $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
            $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

            $offset = ($page - 1) * $rows;

            $sql = "SELECT COUNT(*) FROM " . $table . " WHERE  pesquisarapida LIKE '%" . $_POST['sSearch_0'] . "%'" . " AND identidade ='" . Yii::app()->user->identidade . "' AND iddepartamento ='" . Yii::app()->user->departamento . "' AND idtipodocumento ='" . Yii::app()->user->tipodocumental . "'" ;


            $count = Yii::app()->db->createCommand($sql)->queryScalar();

            $result["iTotalRecords"] = $count;
            $result["iTotalDisplayRecords"] = $count;
            $result["iDisplayStart"] = $page;
            $result["iDisplayLength"] = $rows;

            $limit = $rows;

            $query = "SELECT * FROM " . $table . " WHERE pesquisarapida LIKE '%" . $_POST['sSearch_0'] . "%'" . " AND identidade ='" . Yii::app()->user->identidade . "' AND iddepartamento ='" . Yii::app()->user->departamento . "' AND idtipodocumento ='" . Yii::app()->user->tipodocumental . "'" .
                     "order by 'id' limit " . $limit . " offset " . $offset;

            $connection = Yii::app()->db;
            $command = $connection->createCommand($query);

            $model = $command->query();

            $grid = array();
            $i = 0;

            foreach ($model as $m) {

                $caminho = ('/docPDF/' . $m['caminhoimg']);
                $btnUrl = '<a style="display:floatleft; margin-right:12px; padding:5px 10px;"  class="btn btn-primary btnacao" class="btn" onclick="viewDocument(' . "'" . $caminho . "'" . ')" href="javascript:void(0)"><i class="fa fa-search"></i> Visualizar Documento</a>
                           <div style="font-size:6px;">&nbsp;</div><a style="display:floatleft; padding:5px 10px;" class="btn btn-default" onclick="detalhamento(' . $m['id'] . ')" href="javascript:void(0)">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-list-alt"></i>&nbsp;&nbsp; &nbsp;&nbsp; Detalhamento &nbsp;&nbsp;&nbsp;</a>';
               
//                $grid[0] = $m['id'];
                $grid[0] = $m['nomedocumento'];
                $grid[1] = $m['nomeorigem'];
                $grid[2] = $btnUrl;
                
                $result["aaData"][$i] = $grid;
                $i++;
            }
        } else {
            $result = "";
        }
        echo json_encode($result);
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 9/11/2016
     * data última atualização: 10/11/2016 
     * descrição: 
     *      Carrega o formuário com os dados do usuário.
     */

    public function actionGetDadosTipoDocumento($id) {

        $idtipodoc = TbDepartamentoTipodocumento::model()->findByPk($id)->idtipodocumento;

        Yii::app()->user->tipodocumental = $idtipodoc;

        $table = TbTipodocumento::model()->findByPk($idtipodoc)->tabelautil;
        Yii::app()->user->table = $table;

        $criteria = new CDbCriteria();

        $criteria->condition = 'idtipodoc=:idtipodoc';
        $criteria->params = array(':idtipodoc' => $idtipodoc);
        $criteria->order = "ordem";

        $modelCus = TbCustomizacao::model()->findAll($criteria);

        $arrayCust = array();
        $i = 0;
        foreach ($modelCus as $md) {
            $arrayCust[$i]['titulocampo'] = $md->titulocampo;
            $i++;
        }
        Yii::app()->end(json_encode($arrayCust));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza 
     * data criação: 31/10/2017
     * data última atualização: 31/10/2017 
     * descrição: 
     *      
     */

    public function actionRetornaCampos($id) {
              
        $table = Yii::app()->user->table;
        
        $query = "SELECT idtipodocumento FROM " . $table . " WHERE id =" . $id;
            $connection = Yii::app()->db;
            $command = $connection->createCommand($query);

            $modelD = $command->query();
        
            foreach ($modelD as $m) {  
                $idTipodoc = $m['idtipodocumento'];
            }      
        
       // die("Tipodoc: " . $idTipodoc);
        
        $criteria = new CDbCriteria;        
        $criteria->condition = "idtipodoc=:idtipodoc";
        $criteria->params = array(":idtipodoc" => $idTipodoc);

        $criteria->order = 'ordem';
        
        $model = TbCustomizacao::model()->findAll($criteria);

        $retorno['html'] = "";
        
        foreach ($model as $m) {

            $query = "SELECT * FROM " . $table . " WHERE id =" . $id;
            $connection = Yii::app()->db;
            $command = $connection->createCommand($query);

            $modelD = $command->query();
            
            
            foreach ($modelD as $md) {  
                
                $retorno['html'] = $retorno['html'] . '
                        <div class="row">
                            <section class="col col-lg-12">
                                <label>'.$m->titulocampo . '</label>                    
                                <label class="input">
                                    <input class="form-control" id="' . $m->titulocampo . '" name="' . $m->titulocampo . '" type="text" readonly="true" value="' . $md[$m->nomecampo] . '">                
                                </label>
                            </section>
                        </div>
                    ';   
            }
        }
        
        $retorno["html"] = '<tagextra><fieldset id="htmlCustom">' . $retorno["html"] . '</fieldset></tagextra>';
        
        Yii::app()->end(json_encode($retorno));
    }
    
}