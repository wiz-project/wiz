<?php

/**
 * This is the model class for table "zones_water_request_parameters".
 *
 * The followings are the available columns in table 'zones_water_request_parameters':
 * @property string $parameter
 * @property string $zone
 * @property string $value
 * @property boolean $active
 *
 * The followings are the available model relations:
 * @property WaterRequestParameters $parameter0
 */
class ZonesWaterRequestParameters extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ZonesWaterRequestParameters the static model class
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
		return 'zones_water_request_parameters';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parameter, zone', 'required'),
			array('parameter, zone', 'length', 'max'=>255),
			array('value', 'length', 'max'=>50),
			array('active', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parameter, zone, value, active', 'safe', 'on'=>'search'),
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
			'parameter0' => array(self::BELONGS_TO, 'WaterRequestParameters', 'parameter'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'parameter' => 'Parameter',
			'zone' => 'Zone',
			'value' => 'Value',
			'active' => 'Active',
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

		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}