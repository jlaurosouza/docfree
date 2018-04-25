<?php

/**
 * This is the model class for table "tb_departamento_tipodocumento".
 *
 * The followings are the available columns in table 'tb_departamento_tipodocumento':
 * @property integer $id
 * @property integer $iddepartamento
 * @property integer $idtipodocumento
 * @property string $pathautoindex
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property ConfiguracaoAutoindexacao[] $configuracaoAutoindexacaos
 * @property GedNomedocumento[] $gedNomedocumentos
 * @property TbDepartamento $iddepartamento0
 * @property TbTipodocumento $idtipodocumento0
 * @property TbUsuario $operador0
 * @property TbLoteparaindexar[] $tbLoteparaindexars
 * @property TbTabelabiblioteca[] $tbTabelabibliotecas
 */
class TbDepartamentoTipodocumento extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_departamento_tipodocumento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iddepartamento, idtipodocumento, pathautoindex, operador', 'required'),
			array('iddepartamento, idtipodocumento, operador', 'numerical', 'integerOnly'=>true),
			array('pathautoindex', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iddepartamento, idtipodocumento, pathautoindex, operador', 'safe', 'on'=>'search'),
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
			'configuracaoAutoindexacaos' => array(self::HAS_MANY, 'ConfiguracaoAutoindexacao', 'iddepartamentotipodocumento'),
			'gedNomedocumentos' => array(self::HAS_MANY, 'GedNomedocumento', 'iddepartamentotipodocumento'),
			'iddepartamento0' => array(self::BELONGS_TO, 'TbDepartamento', 'iddepartamento'),
			'idtipodocumento0' => array(self::BELONGS_TO, 'TbTipodocumento', 'idtipodocumento'),
			'operador0' => array(self::BELONGS_TO, 'TbUsuario', 'operador'),
			'tbLoteparaindexars' => array(self::HAS_MANY, 'TbLoteparaindexar', 'iddepartamentotipodocumento'),
			'tbTabelabibliotecas' => array(self::HAS_MANY, 'TbTabelabiblioteca', 'iddepartamentotipodocumento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'iddepartamento' => 'Iddepartamento',
			'idtipodocumento' => 'Idtipodocumento',
			'pathautoindex' => 'Pathautoindex',
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
		$criteria->compare('iddepartamento',$this->iddepartamento);
		$criteria->compare('idtipodocumento',$this->idtipodocumento);
		$criteria->compare('pathautoindex',$this->pathautoindex,true);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbDepartamentoTipodocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
