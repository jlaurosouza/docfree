<?php

/**
 * This is the model class for table "tb_estado".
 *
 * The followings are the available columns in table 'tb_estado':
 * @property integer $id
 * @property string $estado
 * @property string $uf
 *
 * The followings are the available model relations:
 * @property TbAssociado[] $tbAssociados
 * @property TbCidade[] $tbCidades
 * @property TbEntidade[] $tbEntidades
 */
class TbEstado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_estado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'numerical', 'integerOnly'=>true),
			array('estado', 'length', 'max'=>50),
			array('uf', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, estado, uf', 'safe', 'on'=>'search'),
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
			'tbAssociados' => array(self::HAS_MANY, 'TbAssociado', 'idestado'),
			'tbCidades' => array(self::HAS_MANY, 'TbCidade', 'idestado'),
			'tbEntidades' => array(self::HAS_MANY, 'TbEntidade', 'idestado'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'estado' => 'Estado',
			'uf' => 'Uf',
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
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('uf',$this->uf,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbEstado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
