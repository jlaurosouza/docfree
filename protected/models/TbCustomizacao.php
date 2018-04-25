<?php

/**
 * This is the model class for table "tb_customizacao".
 *
 * The followings are the available columns in table 'tb_customizacao':
 * @property integer $idtipodoc
 * @property integer $ordem
 * @property integer $identidade
 * @property string $titulocampo
 * @property string $nomecampo
 * @property string $tipocampo
 * @property string $grupolista
 * @property integer $operador
 *
 * The followings are the available model relations:
 * @property TbEntidade $identidade0
 * @property TbTipodocumento $idtipodoc0
 * @property TbUsuario $operador0
 */
class TbCustomizacao extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_customizacao';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idtipodoc, ordem, identidade, titulocampo, nomecampo, tipocampo, grupolista, operador', 'required'),
			array('idtipodoc, ordem, identidade, operador', 'numerical', 'integerOnly'=>true),
			array('titulocampo, nomecampo', 'length', 'max'=>30),
			array('tipocampo', 'length', 'max'=>20),
			array('grupolista', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idtipodoc, ordem, identidade, titulocampo, nomecampo, tipocampo, grupolista, operador', 'safe', 'on'=>'search'),
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
			'idtipodoc0' => array(self::BELONGS_TO, 'TbTipodocumento', 'idtipodoc'),
			'operador0' => array(self::BELONGS_TO, 'TbUsuario', 'operador'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtipodoc' => 'Idtipodoc',
			'ordem' => 'Ordem',
			'identidade' => 'Identidade',
			'titulocampo' => 'Título do campo',
			'nomecampo' => 'Nome do campo',
			'tipocampo' => 'Selecione o Tipo do campo',
			'grupolista' => 'Grupolista',
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

		$criteria->compare('idtipodoc',$this->idtipodoc);
		$criteria->compare('ordem',$this->ordem);
		$criteria->compare('identidade',$this->identidade);
		$criteria->compare('titulocampo',$this->titulocampo,true);
		$criteria->compare('nomecampo',$this->nomecampo,true);
		$criteria->compare('tipocampo',$this->tipocampo,true);
		$criteria->compare('grupolista',$this->grupolista,true);
		$criteria->compare('operador',$this->operador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbCustomizacao the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
