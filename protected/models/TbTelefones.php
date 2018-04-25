<?php

/**
 * This is the model class for table "tb_telefones".
 *
 * The followings are the available columns in table 'tb_telefones':
 * @property integer $id
 * @property string $tabela
 * @property integer $idtabela
 * @property string $numero
 * @property integer $operadora
 *
 * The followings are the available model relations:
 * @property TbOperadoras $operadora0
 */
class TbTelefones extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_telefones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tabela, idtabela, numero', 'required'),
			array('idtabela, operadora', 'numerical', 'integerOnly'=>true),
			array('tabela', 'length', 'max'=>30),
			array('numero', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tabela, idtabela, numero, operadora', 'safe', 'on'=>'search'),
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
			'operadora0' => array(self::BELONGS_TO, 'TbOperadoras', 'operadora'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tabela' => 'Tabela',
			'idtabela' => 'Idtabela',
			'numero' => 'Numero',
			'operadora' => 'Operadora',
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
		$criteria->compare('tabela',$this->tabela,true);
		$criteria->compare('idtabela',$this->idtabela);
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('operadora',$this->operadora);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbTelefones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
