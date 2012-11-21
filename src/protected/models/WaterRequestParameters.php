<?php

/**
 * This is the model class for table "water_request_parameters".
 *
 * The followings are the available columns in table 'water_request_parameters'
 * @property string $name the name of the parameter
 * @property string $description the description of the parameter
 * @property string $measurement_unit the unit of measurement of the parameter
 */
class WaterRequestParameters extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
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
		return array(

			//primary key
			array('name','unique'),
			
			//max_length
			array('name', 'length', 'max'=>255),
			array('description', 'length', 'max'=>500),
			array('measurement_unit', 'length', 'max'=>50),
			
			//required
			array('name, description', 'required'),
			
			//The following rule is used by search().
			array('name,description,measurement_unit', 'safe', 'on'=>'search'),
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
			'zone_request_parameters'=>array(self::HAS_MANY,'ZonesWaterRequestParameters','parameter','order' => 'zone ASC'),
			'zones'=>array(self::HAS_MANY,'Zones',array('zone'=>'parent_zone_name'),'through'=>'zone_request_parameters','joinType'=>'INNER JOIN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Request Parameter',
			'description' => 'Description',
			'measurement_unit' => 'Measurement unit',
			'zone_request_parameters.zone' => 'Zones',
			'zone_request_parameters.value' => 'Parameter Value',
			'zone_request_parameters.active' => 'Active',
			'zone_request_parameters.required' => 'Required',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	    $criteria=new CDbCriteria;
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('measurement_unit',$this->measurement_unit,true);
	    return new CActiveDataProvider(get_class($this), array(
	        'criteria'=> $criteria,
	        'sort'=>array(
	            'defaultOrder'=>'name ASC',
	        ),
	        'pagination'=>array(
	            'pageSize'=>10
	        ),
	    ));
	}
	
	/**
	 * Retrieves a list of zones associated with the module
	 * @return HTML string, the names of the areas
	 */
	public function zonesToString() {
		$return = '';
		foreach ($this->zone_request_parameters as $zone) {
			$return .= $zone->zone.'<br />';
		}
		
		return $return;
	}
	
	/**
	 * This function internationalize the labels using Yii::t()
	 * @see CActiveRecord::getAttributeLabel()
	 */
	public function getAttributeLabel($attribute)
	{
		$baseLabel = parent::getAttributeLabel($attribute);
		return Yii::t('waterrequest', $baseLabel);
	}
		
}