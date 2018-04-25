<?php

class DefaultController extends Controller {

    public $retorno = array();

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
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
     *      renderiza o o formulario "main/default/index.php"
     */

    public function actionIndex() {
        $this->render('index');
    }

    public function actionExibir() {
        $this->render('exibir');
    }

    public function actionExibirpdf() {
        $this->render('exibirPdf');
    }

    public function actionListacustomizacao() {
        $this->render('listacustomizacao');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 17/08/2016
     * data última atualização: 17/08/2016
     * descrição:
     *  Carrega os campos de customização referentes ao Tipo documental Selecionado.
     *      
     */

    public function actionCarregarCustomizacao() {

        $codigoRealCustomizacao = TbDepartamentoTipodocumento::model()->findByPk($_GET["idtipodoc"])->idtipodocumento;

        $criteria = new CDbCriteria();

        $criteria->condition = 'idtipodoc=:idtipodoc';
        $criteria->params = array(':idtipodoc' => $codigoRealCustomizacao);
        $criteria->order = "ordem";

        $modelCus = TbCustomizacao::model()->findAll($criteria);

        $arrayCust = array();
        $i = 0;
        foreach ($modelCus as $md) {


            $arrayCust[$i]['titulocampo'] = $md->titulocampo;
            $arrayCust[$i]['nomecampo'] = $md->nomecampo;
            $arrayCust[$i]['tipocampo'] = $md->tipocampo;
            $i++;
        }
        Yii::app()->end(json_encode($arrayCust));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 14/12/2015
     * data última atualização: 14/12/2015 
     * descrição: 
     *      
     */

    public function actionGrid() {

        $id = $_POST['sSearch_0'];
        $campo = $_POST['sSearch_1'];
        $descricao = $_POST['sSearch_2'];

        if (!empty($id)) {

            $tabela = TbTipoDocumento::model()->findByPk($id)->tabela_db;

            $rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
            $page = isset($_POST['iDisplayStart']) && !empty($_POST['iDisplayStart']) ? (intval($_POST['iDisplayStart']) / $rows) + 1 : 1;

            $offset = ($page - 1) * $rows;

            if (empty($campo)) {
                $command = Yii::app()->db->createCommand("SELECT * FROM " . $tabela);
            } else {
                $command = Yii::app()->db->createCommand("SELECT * FROM " . $tabela . " WHERE " . $campo . " LIKE '%" . $descricao . "%'");
            }

            $result["iTotalRecords"] = $command->query()->count();
            $result["iTotalDisplayRecords"] = $command->query()->count();
            $result["iDisplayStart"] = $page;
            $result["iDisplayLength"] = $rows;

            if (empty($campo)) {
                $command = Yii::app()->db->createCommand("SELECT * FROM " . $tabela . " limit " . $offset . ", " . $rows);
            } else {
                $command = Yii::app()->db->createCommand("SELECT * FROM " . $tabela . " WHERE " . $campo . " LIKE '%" . $descricao . "%'" . " limit " . $offset . ", " . $rows);
                $command->order($campo);
            }

            $modelGrid = $command->query();

            $grid = array();
            $i = 0;

            foreach ($modelGrid as $key => $value) {

                //$caminhoImg = Yii::app()->createAbsoluteUrl("main/default/exibir/codigo/" . Criptografia::codificar(trim($value['caminhoimg']), CHAVE));
                //$btUrl = '<a  href="' . $caminhoImg . '" target="_blank"> Visualizar documento</a>';
                //Yii::app()->request->baseUrl("/assets/img/logo.png");
                //$caminhoImg = Yii::app()->request->baseUrl($value['caminhoimg']);
                //$btUrl = '<a  href="'. Yii::app()->request->baseUrl . "/" . $value['caminhoimg'] . '"  target="_blank"> Visualizar documento</a>';                
                $btUrl = '<center><div class="">
                        <a  href="' . Yii::app()->request->baseUrl . "/" . $value['caminhoimg'] . '"  target="_blank" id="ToolTables_dtable_0" class="btn btn-info" title="">
                        <span><i class="cus-doc-pdf"></i> Abrir Documento</span></a></div></center>';

                $grid[0] = $value['id'];
                $grid[1] = $value['palavra_chave'] . $value['palavra_chave'];
                $grid[2] = $btUrl;

                $result["aaData"][$i] = $grid;
                $i++;
            }
            echo json_encode($result);
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 20/11/2015
     * data última atualização: 20/11/2015 
     * descrição: 
     *      Carrega as cidades de acordo com o estado selecionado.
     */

    public function actionCarregarCidades() {

        $dados = TbCidade::model()->findAll('idestado=:estado', array(':estado' => (int) $_POST["codigoEstado"]));
        $data = CHtml::listData($dados, 'id', 'cidade');

        $arrayCidades = array();
        $i = 0;
        foreach ($data as $value => $name) {
            $arrayCidades[$i]['id'] = $value;
            $arrayCidades[$i]['cidade'] = CHtml::encode($name);
            $i++;
        }

        Yii::app()->end(json_encode($arrayCidades));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 25/05/2016
     * data última atualização: 25/05/2016 
     * descrição: 
     *      Carrega os tipos documentais de acordo com o departamento selecionado.
     */

    public function actionCarregarTipodoc() {

        Yii::app()->user->departamento = $_POST["codigoDepartamento"];

        $dados = TbDepartamentoTipodocumento::model()->findAll('iddepartamento=:iddepartamento', array(':iddepartamento' => (int) $_POST["codigoDepartamento"]));
        $data = CHtml::listData($dados, 'id', 'tipodocumento');

        $arrayTipodoc = array();
        $i = 0;

        $arrayTipodoc[$i]['id'] = "";
        $arrayTipodoc[$i]['tipodocumento'] = '••• Selecione o tipo documental desejado •••';
        $i++;
        foreach ($data as $key => $value) {

            $md = TbDepartamentoTipodocumento::model()->findBypk($key);

            $arrayTipodoc[$i]['id'] = $key;
            $arrayTipodoc[$i]['tipodocumento'] = $md->idtipodocumento0['nome'];
            $i++;
        }

        Yii::app()->end(json_encode($arrayTipodoc));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 18/08/2016
     * data última atualização: 18/08/2016 
     * descrição: 
     *      Carrega os campos do tipo seleção referente os tipo de documento.
     */

    public function actionCarregarCampoSel() {

        $idtipodocumento = TbDepartamentoTipodocumento::model()->findByPk($_POST['idtipodoc'])->idtipodocumento;

        $camporeferencia = $_POST['camporef'];

        $dados = Biblioteca::model()->findAll('idtipodocumento=:idtipodocumento and camporeferencia=:camporeferencia', array(':idtipodocumento' => (int) $idtipodocumento, ':camporeferencia' => $camporeferencia));
        //$data = CHtml::listData($dados, 'id', 'nomechave');

        $arrayChave = array();
        $i = 0;

        $arrayChave[$i]['id'] = 0;
        $arrayChave[$i]['nomechave'] = '••• Selecione uma opção •••';
        $i++;
        foreach ($dados as $md) {
            // die("VAlor:" . $value);
            $arrayChave[$i]['id'] = $md->id;
            $arrayChave[$i]['nomechave'] = $md->nomechave;
            $i++;
        }
        Yii::app()->end(json_encode($arrayChave));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 17/08/2016
     * data última atualização: 17/08/2016
     * descrição:
     *  Carrega os campos de customização referentes ao Tipo documental Selecionado.
     *      
     */

    public function actionDadosCustomizacao() {

        $idtipodoc = TbDepartamentoTipodocumento::model()->findByPk($_POST["idtipodoc"])->idtipodocumento;

        $criteria = new CDbCriteria();

        $criteria->condition = 'idtipodoc=:idtipodoc';
        $criteria->params = array(':idtipodoc' => $idtipodoc);
        $criteria->order = "ordem";

        $model = TbCustomizacao::model()->findAll($criteria);

        $dados = array();
        $i = 0;
        foreach ($model as $md) {
            $dados[$i]['titulocampo'] = $md->titulocampo;
            $dados[$i]['nomecampo'] = $md->nomecampo;
            $i++;
        }
        //print_r($dados[1]["nomecampo"]);
        Yii::app()->end(json_encode($dados));
    }

    /*
     * 	Função de busca de Endereço pelo CEP
     * 	-	Desenvolvido Felipe Olivaes para ajaxbox.com.br
     * 	-	Utilizando WebService de CEP da republicavirtual.com.br     
     * atualizado por: jlaurosouza
     * data última atualização: 05/11/2017
     * descrição: 
     *          
     * 
     * 
     */

    public function actionBuscarcep() {

        $cep = $_POST['cep'];

        $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep=' . urlencode($cep) . '&formato=query_string');

        if (!$resultado) {
            $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";

            parse_str($resultado, $this->retorno);

            if ($this->retorno['resultado_txt'] == "erro ao buscar cep") {
                $this->retorno['resultado_txt'] = "Serviço indisponível, Falha com a Rede. (Tente novamente)";
            }
        } else {

            parse_str($resultado, $this->retorno);
            //die($this->retorno['resultado_txt']);
            $this->retorno['resultado_txt'] = Util::convertToUTF8($this->retorno['resultado_txt']);

            if ($this->retorno['resultado_txt'] == "sucesso - cep completo") {

                //Saber o Estado
                $criteria = new CDbCriteria();
                $criteria->condition = "uf=:uf";
                $criteria->params = array(":uf" => $this->retorno['uf']);

                $estado = TbEstado::model()->find($criteria);

                $this->retorno['iduf'] = $estado->id;
                $this->retorno['uf'] = $estado->estado;

                //fim
                //Saber a Cidade

                $this->retorno['cidade'] = Util::convertToUTF8($this->retorno['cidade']);

                $criteria->condition = "idestado=:idestado AND cidade=:cidade";
                $criteria->params = array(":idestado" => $this->retorno['iduf'], ":cidade" => $this->retorno['cidade']);

                $count = TbCidade::model()->count($criteria);

                if ($count == 0) {
                    $model = new TbCidade();

                    $model->cidade = $this->retorno['cidade'];
                    $model->idestado = $this->retorno['iduf'];

                    $this->retorno['idcidade'] = $model->id;

                    $model - save();
                } else {
                    $this->retorno['idcidade'] = TbCidade::model()->find($criteria)->id;
                }

                //fim
//                $this->retorno['bairro'] = Util::replaceCaracterEspecial(mb_convert_encoding($this->retorno['bairro'], "UTF-8"));
//                $this->retorno['tipo_logradouro'] = Util::replaceCaracterEspecial(mb_convert_encoding($this->retorno['tipo_logradouro'], "UTF-8"));
//                $this->retorno['logradouro'] = Util::replaceCaracterEspecial(mb_convert_encoding($this->retorno['logradouro'], "UTF-8"));

                $this->retorno['bairro'] = Util::convertToUTF8($this->retorno['bairro']);
                $this->retorno['tipo_logradouro'] = Util::convertToUTF8($this->retorno['tipo_logradouro']);
                $this->retorno['logradouro'] = Util::convertToUTF8($this->retorno['logradouro']);
            } else {
                $this->retorno['resultado_txt'] = "CEP não encontrado";
            }
        }

        Yii::app()->end(json_encode($this->retorno));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 24/08/2017
     * data última atualização: 05/11/2017 
     * descrição: 
     *      Retorna a Lista das cidades de acordo com o estado selecionado.
     */

    public function actionGetListaCidade() {

        $criteria = new CDbCriteria();
        $criteria->condition = "idestado=:idestado";
        $criteria->params = array(':idestado' => (int) $_GET["idestado"]);

        $dados = TbCidade::model()->findAll($criteria);

        $data = CHtml::listData($dados, 'id', 'cidade');

        $arrayCidade = array();
        $i = 0;
        foreach ($data as $key => $value) {
            $arrayCidade[$i]['id'] = $key;
            $arrayCidade[$i]['cidade'] = $value;
            $i++;
        }
        Yii::app()->end(json_encode($arrayCidade));
    }

    /*
     * autor: jlaurosouza
     * atualizado por: jlaurosouza
     * data criação: 25/08/2017
     * data última atualização: 25/08/2017 
     * descrição:       
     *      Recebe as informações viu POST do formulário de cadastro da cidade e inclui a nova cidade na base de dados.
     * 
     */

    public function actionCreateCidade() {

        $model = new TbCidade();

        if ($_POST) {

            $this->retorno['tipo'] = "SUCESSO";
            $this->retorno['msg'] = "ok";

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            try {

                //Variável referente ao estado
                $estado = trim($_POST['estado']);
                if (empty($estado)) {
                    throw new Exception("O <strong>Estado</strong> não pode ser vazio.");
                }

                //Variável referente ao cidade
                $cidade = trim($_POST['cidade']);
                if (empty($cidade)) {
                    throw new Exception("A <strong>Cidade</strong> não pode ser vazio.");
                }

                //Verificar se a Cidade Existe
                if (Util::verificarCidadeExiste($estado, $cidade)) {
                    throw new Exception("<strong>Cidade</strong> já existe.");
                }

                $model->cidade = $cidade;
                $model->idestado = $estado;

                if ($model->validate()) {

                    if (!$model->save()) {
                        throw new Exception("Falha ao tentar salvar");
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
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 08/11/2017
     * data última atualização: 08/11/2017 
     * descrição: 
     *      Retorna o nome do Tipo Documental
     */

    public function ActionGetNomeTipoDocumento() {

        $criteria = new CDbCriteria();

        $criteria->condition = "id=:id";
        $criteria->params = array(":id" => (int) $_POST["id"]);

        $count = TbTipodocumento::model()->count($criteria);

        $array = array();

        if ($count > 0) {
            $array[0]['nome'] = TbTipodocumento::model()->find($criteria)->nome;

            $criteria->condition = 'idtipodoc = :idtipodoc';
            $criteria->params = array(':idtipodoc' => (int) $_POST["id"]);

            $array[0]['qtd'] = TbCustomizacao::model()->count($criteria);

            $criteria->condition = "idtipodoc=:idtipodoc and ordem=:ordem";
            $criteria->params = array(":idtipodoc" => (int) $_POST["id"], ":ordem" => '0');

            $array[0]['ordem'] = TbCustomizacao::model()->count($criteria);
        } else {
            $array[0]['nome'] = "";
            $array[0]['qtd'] = 0;
            $array[0]['ordem'] = 0;
        }
        Yii::app()->end(json_encode($array));
    }

}
