<?php

/**
 * This is the model class for table "biblioteca".
 *
 * The followings are the available columns in table 'biblioteca':
 * @property integer $id
 * @property string $nomechave
 * @property integer $identidade
 * @property integer $idtipodocumento
 * @property string $camporeferencia
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property TbUsuario $operador0
 * @property TbEntidade $identidade0
 * @property TbTipodocumento $idtipodocumento0
 */
class Biblioteca extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'biblioteca';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nomechave, identidade, idtipodocumento, camporeferencia, operador', 'required'),
			array('identidade, idtipodocumento, operador', 'numerical', 'integerOnly'=>true),
			array('nomechave, camporeferencia', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nomechave, identidade, idtipodocumento, camporeferencia, operador', 'safe', 'on'=>'search'),
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
			'operador0' => array(self::BELONGS_TO, 'TbUsuario', 'operador'),
			'identidade0' => array(self::BELONGS_TO, 'TbEntidade', 'identidade'),
			'idtipodocumento0' => array(self::BELONGS_TO, 'TbTipodocumento', 'idtipodocumento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nomechave' => 'Nomechave',
			'identidade' => 'Identidade',
			'idtipodocumento' => 'Idtipodocumento',
			'camporeferencia' => 'Camporeferencia',
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
		$criteria->compare('nomechave',$this->nomechave,true);
		$criteria->compare('identidade',$this->identidade);
		$criteria->compare('idtipodocumento',$this->idtipodocumento);
		$criteria->compare('camporeferencia',$this->camporeferencia,true);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Biblioteca the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
