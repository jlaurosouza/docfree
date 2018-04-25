<?php

class DefaultController extends Controller {

    public $layout = '//layouts/main-login';
    
    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      renderiza o o formulario "login/default/index.php".
     */

    public function actionIndex() {
        
        $this->render('index');
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Valida o usuario que esta acessando o sistema.
     */

    public function actionLogar() {
       
        $model = new LoginForm();

        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $lembrar = $_POST['lembrar'];
        
        if ($model->login($usuario, $senha, $lembrar)) {
            //$this->salvarUltimoacesso(Yii::app()->user->id);
            Yii::app()->end("1");
        } else {
            Yii::app()->end("0");
        }
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 19/11/2015
     * data última atualização: 19/11/2015 
     * descrição: 
     *      Verifica se o é o primeiro acesso (COLUNA:primeiroacesso) do usuário e a preenche.
     *      Atualiza a data do ultimo acesso (COLUNA:ultimoacesso).
     * Tabela: Usuarios 
     */

    private function salvarUltimoacesso($id) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id=:id";
        $criteria->params = array(":id" => $id);

        $usuario = TbUsuario::model()->find($criteria);

        if ($usuario->primeiroacesso == "") {
            $usuario->primeiroacesso = date("Y-m-d H:i:s");
        }
        $usuario->ultimoacesso = date("Y-m-d H:i:s");
        $usuario->save();
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      renderiza o o formulario "login/default/recuperasenha.php",
     *      o qual o usuario informara o email para a recuperação de senha.
     */

    public function actionRecuperarsenha() {
        $this->render("recuperarsenha");
    }

    /*
     * autor: jlaurosouza
     * atualizado por: 
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Gera o token, salva na Tabela de usuário (COLUNA:tokenusuario)
     *      e envia um email para a recuperação da conta.
     */

    public function actionRecuperarsenhaexe() {

        $retorno = array();
        $email = trim($_POST['email']);

        try {

            if (empty($email)) {
                throw new Exception("Digite seu e-mail");
            }

            if (!$this->checarEmail($email)) {
                throw new Exception("E-mail não cadastrado");
            }

            $token = substr(SHA1(uniqid(rand(), true)), 0, 30);

            $criteria = new CDbCriteria();
            $criteria->condition = "email=:email and ativo!=:ativo and deletado=:deletado";
            $criteria->params = array(":email" => $email, ":ativo" => "N", ":deletado" => "N");

            $model = TbUsuario::model()->find($criteria);
            $model->tokenusuario = $token;
            if (!$model->save()) {
                throw new Exception("Falha ao tentar salvar token");
            }

            $parametros["email"] = $email;
            $parametros["assunto"] = "Docfree - Redefinição de senha";
            $parametros["mensagem"] = "<a href='" . Yii::app()->createAbsoluteUrl('/login/default/redefinirsenha/t/' . $token) . "'>" . Yii::app()->createAbsoluteUrl('/login/default/redefinirsenha/t/' . $token) . "</a>";

            if (Email::enviarEmail($parametros)) {
                $retorno['tipo'] = "sucesso";
                $retorno['msg'] = "E-mail enviado com sucesso";
            } else {
                throw new Exception('Falha ao enviar e-mail de redefinição de senha');
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
     * data criação: 17/11/2015
     * data última atualização: 17/11/2015 
     * descrição: 
     *      Checa se o email informado existe, se esta ativo e não deletado na base de dados.
     */

    private function checarEmail($email) {

        $criteria = new CDbCriteria();
        $criteria->condition = "email=:email and ativo!=:ativo and deletado=:deletado";
        $criteria->params = array(":email" => $email, ":ativo" => "N", ":deletado" => "N");

        $total = TbUsuario::model()->count($criteria);

        if ($total > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(array("index"));
    }

}
