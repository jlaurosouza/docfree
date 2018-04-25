<?php

spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once(Yii::app()->basePath . '/components/Canvas.php');
spl_autoload_register(array('YiiBase', 'autoload'));

class IndexacaoController extends Controller {
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
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 28/10/2016
     * data última atualização: 03/11/2016 
     * descrição: 
     *      Direciona para o _Form se não receber parametro POST.
     *      Se receber um POST verifica e adiciona o novo registro a base de dados.
     */

    public function actionCreate() {

        $model = new TbTipodocumento();

        $retorno = "NOVO";

        if ($_POST) {
            //die($_FILES['file']['name']);
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

            $tipodoc = TbDepartamentoTipodocumento::model()->findByPk($tipodoc)->idtipodocumento;
            $nometipodoc = TbTipodocumento::model()->findByPk($tipodoc)->nome;

            $tabelautil = TbTipodocumento::model()->findByPk($tipodoc)->tabelautil;

            $arrayCust = array();

            $arrayCust = Util::camposCustomizacao($tipodoc);

            $camposInsertArray = ' ';
            $valueInsertArray = ' ';

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            $pesquisarRapida = "";

            /* TESTANDO O PREENCHIMENTOS DOS CAMPOS DE CUSTOMIZAÇÃO */
            try {
                foreach ($arrayCust as $md) {
                    $nomecampo = $md['nomecampo'];
                    $titulocampo = $md['titulocampo'];

                    if (empty($_POST["$nomecampo"])) {
                        throw new Exception("O Campo, <strong>$titulocampo</strong>, não pode ser vazio.");
                    }

                    $camposInsertArray = $camposInsertArray . ", `" . $nomecampo . "`";
                    $valueInsertArray = $valueInsertArray . ", '" . $_POST["$nomecampo"] . "' ";

                    $pesquisarRapida = $pesquisarRapida . "," . $_POST["$nomecampo"];
                }

                /* FIM DO TESTE */

                /* Variavel recebe numeração para o arquivo decorrente, referente ao nome do documento */
                $newfile = Util::nextFile(Yii::app()->user->identidade);
                /* Variavel recebe numeração referente ao nome do lote para armazenamento */
                $lote = Yii::app()->user->identidade . $departamento . $tipodoc;

                /* file */
                if ($_FILES) {
                    if (!empty($_FILES['file']["name"])) {

                        $nomeFile = preg_replace('/\.[^.]*$/', '', $_FILES['file']['name']);
                        $nomeImagem = $nomeFile; //md5(uniqid(rand(), true));
                        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                        $nomeImagem = Util::replaceCaracterEspecial($nomeImagem);

                        /* variavel recebe nome original da imagem com extensão */
                        $nomeOrigemFile = $nomeImagem . "." . $ext;
                    } else {
                        throw new Exception("Falha ao tentar realizar upload do arquivo.");
                    }
                }

                $caminhoimg = str_pad($newfile, 12, "0", STR_PAD_LEFT) . "." . $ext;

                /* SALVAR OS DADOS NA TABELA REFERENTE (DATABASE) */

                $insert = "`identidade`, `iddepartamento`, 
                        `idtipodocumento`, `nometipodoc`, `lote`, `caminhoimg`, `status`, 
                        `nomeorigem`, `datainicio`, `datahoraindex`, `palavrachave`, `pesquisarapida`, 
                        `operador`" . $camposInsertArray;

                $value = Yii::app()->user->identidade . ", " . $departamento . ", " .
                        $tipodoc . ", '" . $nometipodoc . "', " . $lote . ", '" . $lote . "/" . $caminhoimg . "', 2, '" .
                        $nomeOrigemFile . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', " .
                        "'" . $pesquisarRapida . "', '" . $pesquisarRapida . "', " . Yii::app()->user->id . $valueInsertArray;

                $command = Yii::app()->db->createCommand("INSERT INTO `" . $tabelautil . "` (" . $insert . ")VALUES(" . $value . ")");
                $command->execute();

                /* VERIFICAR DIRETÓRIO PARA UPLOAD DO ARQUIVO */
                $retorno = Util::verificaFolderExist($lote);

                /* COPIAR O DOCUMENTO PARA O DIRETÓRIO */
                move_uploaded_file($_FILES['file']['tmp_name'], $this->caminhoDoc . $lote . "/" . $caminhoimg);
                $transaction->commit();
                $retorno = "SALVO";
                //$this->redirect(Yii::app()->createAbsoluteUrl('main/associados/logomarca/ce/' . $ce));
            } catch (Exception $ex) {
                $transaction->rollBack();
                $retorno = "ERRO";
                $model->addError('', $ex->getMessage());
            }
        }
        $this->render('create', array('model' => $model, 'retorno' => $retorno));
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

}