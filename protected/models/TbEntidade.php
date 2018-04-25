<?php

/**
 * This is the model class for table "tb_entidade".
 *
 * The followings are the available columns in table 'tb_entidade':
 * @property integer $id
 * @property string $nomeempresa
 * @property string $nomefantasia
 * @property string $cnpj
 * @property string $home
 * @property string $email
 * @property string $logomarca
 * @property string $responsavel
 * @property string $emailresponsavel
 * @property string $cep
 * @property integer $idestado
 * @property integer $idcidade
 * @property string $bairro
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $datacadastro
 * @property string $status
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property ConfiguracaoAutoindexacao[] $configuracaoAutoindexacaos
 * @property TbAssociadoFuncionario[] $tbAssociadoFuncionarios
 * @property TbCustomizacao[] $tbCustomizacaos
 * @property TbDepartamento[] $tbDepartamentos
 * @property TbCidade $idcidade0
 * @property TbEstado $idestado0
 * @property TbFolderpath[] $tbFolderpaths
 * @property TbNextlote[] $tbNextlotes
 * @property TbNivel[] $tbNivels
 * @property TbTipodocumento[] $tbTipodocumentos
 * @property TbUsuario[] $tbUsuarios
 */
class TbEntidade extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_entidade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nomeempresa, nomefantasia, responsavel, emailresponsavel, datacadastro, operador', 'required'),
			array('idestado, idcidade, operador', 'numerical', 'integerOnly'=>true),
			array('nomeempresa, nomefantasia, cnpj', 'length', 'max'=>50),
			array('home, email, responsavel, emailresponsavel, bairro, logradouro, complemento', 'length', 'max'=>30),
			array('logomarca', 'length', 'max'=>255),
			array('cep, numero', 'length', 'max'=>10),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nomeempresa, nomefantasia, cnpj, home, email, logomarca, responsavel, emailresponsavel, cep, idestado, idcidade, bairro, logradouro, numero, complemento, datacadastro, status, operador', 'safe', 'on'=>'search'),
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
			'configuracaoAutoindexacaos' => array(self::HAS_MANY, 'ConfiguracaoAutoindexacao', 'identidade'),
			'tbAssociadoFuncionarios' => array(self::HAS_MANY, 'TbAssociadoFuncionario', 'identidade'),
			'tbCustomizacaos' => array(self::HAS_MANY, 'TbCustomizacao', 'identidade'),
			'tbDepartamentos' => array(self::HAS_MANY, 'TbDepartamento', 'identidade'),
			'idcidade0' => array(self::BELONGS_TO, 'TbCidade', 'idcidade'),
			'idestado0' => array(self::BELONGS_TO, 'TbEstado', 'idestado'),
			'tbFolderpaths' => array(self::HAS_MANY, 'TbFolderpath', 'identidade'),
			'tbNextlotes' => array(self::HAS_MANY, 'TbNextlote', 'identidade'),
			'tbNivels' => array(self::HAS_MANY, 'TbNivel', 'identidade'),
			'tbTipodocumentos' => array(self::HAS_MANY, 'TbTipodocumento', 'identidade'),
			'tbUsuarios' => array(self::HAS_MANY, 'TbUsuario', 'identidade'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nomeempresa' => 'Nomeempresa',
			'nomefantasia' => 'Nomefantasia',
			'cnpj' => 'Cnpj',
			'home' => 'Home',
			'email' => 'Email',
			'logomarca' => 'Logomarca',
			'responsavel' => 'Responsavel',
			'emailresponsavel' => 'Emailresponsavel',
			'cep' => 'Cep',
			'idestado' => 'Idestado',
			'idcidade' => 'Idcidade',
			'bairro' => 'Bairro',
			'logradouro' => 'Logradouro',
			'numero' => 'Numero',
			'complemento' => 'Complemento',
			'datacadastro' => 'Datacadastro',
			'status' => 'Status',
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
		$criteria->compare('nomeempresa',$this->nomeempresa,true);
		$criteria->compare('nomefantasia',$this->nomefantasia,true);
		$criteria->compare('cnpj',$this->cnpj,true);
		$criteria->compare('home',$this->home,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('logomarca',$this->logomarca,true);
		$criteria->compare('responsavel',$this->responsavel,true);
		$criteria->compare('emailresponsavel',$this->emailresponsavel,true);
		$criteria->compare('cep',$this->cep,true);
		$criteria->compare('idestado',$this->idestado);
		$criteria->compare('idcidade',$this->idcidade);
		$criteria->compare('bairro',$this->bairro,true);
		$criteria->compare('logradouro',$this->logradouro,true);
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('complemento',$this->complemento,true);
		$criteria->compare('datacadastro',$this->datacadastro,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbEntidade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
