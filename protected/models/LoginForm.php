<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CActiveRecord {

    public $rememberMe;
    private $_identity;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tb_usuario';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('usuario, senha', 'required', 'message' => 'O campo <strong>{attribute}</strong> deve ser preenchido.'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('senha', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => 'mantenha-me conectado',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($usuario, $senha) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($usuario, $senha);            
            if (!$this->_identity->authenticate()){  
                $this->addError('senha', 'Usuário ou senha incorreto.');
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login($usuario, $senha, $lembrar) {
        
        $this->rememberMe = $lembrar;
        
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($usuario, $senha);
            if (!$this->_identity->authenticate()){       
                $this->addError('senha', 'Usuário ou senha incorreto.');
            }
        }
        
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days  
            Yii::app()->user->login($this->_identity, $duration);            
            return true;
        }
        else{            
            return false;            
        }
            
    }

}
