<?php

/**
 * This is the model class for table "water_request_parameters".
 *
 * The followings are the available columns in table 'water_request_parameters':
 * @property string $name
 * @property string $description
 * @property string $measurement_unit
 *
 * The followings are the available model relations:
 * @property ZonesWaterRequestParameters[] $zonesWaterRequestParameters
 */
class WaterRequestParameters extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterRequestParameters the static model class
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
		return 'water_request_parameters';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('description', 'length', 'max'=>500),
			array('measurement_unit', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, description, measurement_unit', 'safe', 'on'=>'search'),
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
			'zonesWaterRequestParameters' => array(self::HAS_MANY, 'ZonesWaterRequestParameters', 'parameter'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'description' => 'Description',
			'measurement_unit' => 'Measurement Unit',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('measurement_unit',$this->measurement_unit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}