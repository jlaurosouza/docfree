<?php

/**
 * This is the model class for table "tb_tipodocumento".
 *
 * The followings are the available columns in table 'tb_tipodocumento':
 * @property integer $id
 * @property integer $identidade
 * @property string $nome
 * @property string $tabelautil
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property TbDepartamentoTipodocumento[] $tbDepartamentoTipodocumentos
 * @property TbEntidade $identidade0
 * @property TbUsuario $operador0
 */
class TbTipodocumento extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_tipodocumento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('identidade, nome, tabelautil, operador', 'required'),
			array('identidade, operador', 'numerical', 'integerOnly'=>true),
			array('nome', 'length', 'max'=>50),
			array('tabelautil', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, identidade, nome, tabelautil, operador', 'safe', 'on'=>'search'),
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
			'tbDepartamentoTipodocumentos' => array(self::HAS_MANY, 'TbDepartamentoTipodocumento', 'idtipodocumento'),
			'identidade0' => array(self::BELONGS_TO, 'TbEntidade', 'identidade'),
			'operador0' => array(self::BELONGS_TO, 'TbUsuario', 'operador'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'identidade' => 'Identidade',
			'nome' => 'Nome',
			'tabelautil' => 'Tabelautil',
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
		$criteria->compare('identidade',$this->identidade);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('tabelautil',$this->tabelautil,true);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbTipodocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
