<?php

/**
 * This is the model class for table "macro_area_sources".
 *
 * The followings are the available columns in table 'macro_area_sources':
 * @property integer $id
 * @property text $stato,
 * @property text $provincia,
 * @property text $comune,
 * @property text $cespite,
 * @property text $descrizione,
 * @property text $desc_macro,
 */
class MacroArea extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterSupply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'macro_area_sources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'stato' => 'Stato',
			'provincia' => 'Provincia',
			'comune' => 'Comune',
			'cespite' => 'Cespite',
			'descrizione' => 'Descrizione',
			'desc_macro' => 'Descrizione Macro Area',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('desc_macro',$this->desc_macro);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}