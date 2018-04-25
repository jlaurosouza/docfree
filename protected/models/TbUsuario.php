<?php

/**
 * This is the model class for table "tb_usuario".
 *
 * The followings are the available columns in table 'tb_usuario':
 * @property integer $id
 * @property string $nome
 * @property string $email
 * @property string $usuario
 * @property string $senha
 * @property string $avata
 * @property integer $identidade
 * @property integer $idassociado
 * @property string $keycode
 * @property string $dataprimeiroacesso
 * @property string $dataultimoacesso
 * @property integer $idnivel
 * @property string $autorizador
 * @property string $datacadastro
 * @property string $status
 * @property string $tipousuario
 * @property integer $idfuncionario
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property ConfiguracaoAutoindexacao[] $configuracaoAutoindexacaos
 * @property GedNomedocumento[] $gedNomedocumentos
 * @property LogOperacao[] $logOperacaos
 * @property TbAssociado[] $tbAssociados
 * @property TbAssociadoFuncionario[] $tbAssociadoFuncionarios
 * @property TbCustomizacao[] $tbCustomizacaos
 * @property TbDepartamento[] $tbDepartamentos
 * @property TbDepartamentoTipodocumento[] $tbDepartamentoTipodocumentos
 * @property TbDocumentos[] $tbDocumentoses
 * @property TbFileextension[] $tbFileextensions
 * @property TbFolderpath[] $tbFolderpaths
 * @property TbLicenca[] $tbLicencas
 * @property TbLoteparaindexar[] $tbLoteparaindexars
 * @property TbNextlote[] $tbNextlotes
 * @property TbTabelabiblioteca[] $tbTabelabibliotecas
 * @property TbTipodocumento[] $tbTipodocumentos
 * @property TbAssociado $idassociado0
 * @property TbEntidade $identidade0
 * @property TbNivel $idnivel0
 * @property TbUsuario $operador0
 * @property TbUsuario[] $tbUsuarios
 * @property TbUsuarioEntidadeAssociadoDepartamento[] $tbUsuarioEntidadeAssociadoDepartamentos
 * @property TbUsuarioEntidadeAssociadoDepartamento[] $tbUsuarioEntidadeAssociadoDepartamentos1
 */
class TbUsuario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nome, email, usuario, senha, identidade, idnivel, datacadastro, status, operador', 'required'),
			array('identidade, idassociado, idnivel, idfuncionario, operador', 'numerical', 'integerOnly'=>true),
			array('nome, email, usuario', 'length', 'max'=>30),
			array('senha, avata, keycode', 'length', 'max'=>255),
			array('autorizador, status, tipousuario', 'length', 'max'=>1),
			array('dataprimeiroacesso, dataultimoacesso', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nome, email, usuario, senha, avata, identidade, idassociado, keycode, dataprimeiroacesso, dataultimoacesso, idnivel, autorizador, datacadastro, status, tipousuario, idfuncionario, operador', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'configuracaoAutoindexacaos' => array(self::HAS_MANY, 'ConfiguracaoAutoindexacao', 'operador'),
			'gedNomedocumentos' => array(self::HAS_MANY, 'GedNomedocumento', 'operador'),
			'logOperacaos' => array(self::HAS_MANY, 'LogOperacao', 'operador'),
			'tbAssociados' => array(self::HAS_MANY, 'TbAssociado', 'operador'),
			'tbAssociadoFuncionarios' => array(self::HAS_MANY, 'TbAssociadoFuncionario', 'operador'),
			'tbCustomizacaos' => array(self::HAS_MANY, 'TbCustomizacao', 'operador'),
			'tbDepartamentos' => array(self::HAS_MANY, 'TbDepartamento', 'operador'),
			'tbDepartamentoTipodocumentos' => array(self::HAS_MANY, 'TbDepartamentoTipodocumento', 'operador'),
			'tbDocumentoses' => array(self::HAS_MANY, 'TbDocumentos', 'operador'),
			'tbFileextensions' => array(self::HAS_MANY, 'TbFileextension', 'operador'),
			'tbFolderpaths' => array(self::HAS_MANY, 'TbFolderpath', 'operador'),
			'tbLicencas' => array(self::HAS_MANY, 'TbLicenca', 'operador'),
			'tbLoteparaindexars' => array(self::HAS_MANY, 'TbLoteparaindexar', 'operador'),
			'tbNextlotes' => array(self::HAS_MANY, 'TbNextlote', 'operador'),
			'tbTabelabibliotecas' => array(self::HAS_MANY, 'TbTabelabiblioteca', 'operador'),
			'tbTipodocumentos' => array(self::HAS_MANY, 'TbTipodocumento', 'operador'),
			'idassociado0' => array(self::BELONGS_TO, 'TbAssociado', 'idassociado'),
			'identidade0' => array(self::BELONGS_TO, 'TbEntidade', 'identidade'),
			'idnivel0' => array(self::BELONGS_TO, 'TbNivel', 'idnivel'),
			'operador0' => array(self::BELONGS_TO, 'TbUsuario', 'operador'),
			'tbUsuarios' => array(self::HAS_MANY, 'TbUsuario', 'operador'),
			'tbUsuarioEntidadeAssociadoDepartamentos' => array(self::HAS_MANY, 'TbUsuarioEntidadeAssociadoDepartamento', 'idusuario'),
			'tbUsuarioEntidadeAssociadoDepartamentos1' => array(self::HAS_MANY, 'TbUsuarioEntidadeAssociadoDepartamento', 'operador'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nome' => 'Nome',
			'email' => 'E-mail',
			'usuario' => 'Usuário',
			'senha' => 'Senha',
			'avata' => 'Avata',
			'identidade' => 'Identidade',
			'idassociado' => 'Idassociado',
			'keycode' => 'Keycode',
			'dataprimeiroacesso' => 'Dataprimeiroacesso',
			'dataultimoacesso' => 'Dataultimoacesso',
			'idnivel' => 'Nível de acesso',
			'autorizador' => 'Autorizador',
			'datacadastro' => 'Datacadastro',
			'status' => 'Status',
			'tipousuario' => 'Tipousuario',
			'idfuncionario' => 'Idfuncionario',
			'operador' => 'Operador',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('usuario',$this->usuario,true);
		$criteria->compare('senha',$this->senha,true);
		$criteria->compare('avata',$this->avata,true);
		$criteria->compare('identidade',$this->identidade);
		$criteria->compare('idassociado',$this->idassociado);
		$criteria->compare('keycode',$this->keycode,true);
		$criteria->compare('dataprimeiroacesso',$this->dataprimeiroacesso,true);
		$criteria->compare('dataultimoacesso',$this->dataultimoacesso,true);
		$criteria->compare('idnivel',$this->idnivel);
		$criteria->compare('autorizador',$this->autorizador,true);
		$criteria->compare('datacadastro',$this->datacadastro,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('tipousuario',$this->tipousuario,true);
		$criteria->compare('idfuncionario',$this->idfuncionario);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbUsuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
