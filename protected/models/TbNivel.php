<?php

/**
 * This is the model class for table "tb_nivel".
 *
 * The followings are the available columns in table 'tb_nivel':
 * @property integer $id
 * @property string $nivel
 * @property integer $identidade
 * @property string $status
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property TbEntidade $identidade0
 * @property TbUsuario[] $tbUsuarios
 * @property TbUsuarioacesso[] $tbUsuarioacessos
 */
class TbNivel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_nivel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nivel, identidade, operador', 'required'),
			array('identidade, operador', 'numerical', 'integerOnly'=>true),
			array('nivel', 'length', 'max'=>30),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nivel, identidade, status, operador', 'safe', 'on'=>'search'),
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
			'identidade0' => array(self::BELONGS_TO, 'TbEntidade', 'identidade'),
			'tbUsuarios' => array(self::HAS_MANY, 'TbUsuario', 'idnivel'),
			'tbUsuarioacessos' => array(self::HAS_MANY, 'TbUsuarioacesso', 'idnivel'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nivel' => 'Nivel',
			'identidade' => 'Identidade',
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
		$criteria->compare('nivel',$this->nivel,true);
		$criteria->compare('identidade',$this->identidade);
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
	 * @return TbNivel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
