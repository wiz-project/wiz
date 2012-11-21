<?php

/**
 * This is the model class for table "zones_water_request_parameters".
 *
 * The followings are the available columns in table 'zones_water_request_parameters'
 * @property string $parameter the name of parameter
 * @property string $zone the name of the area to which the parameter is associated
 * @property string $value the parameter value in the reference zone
 * @property boolean $active
 * @property boolean $required
 */
class ZonesWaterRequestParameters extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
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
		return array(

			//composite primary key validator
			array('zone_parameter_identifier', 'CompositeUniqueKeyValidator', 'keyColumns' => 'parameter, zone'),
			
			//max_length
			array('parameter,zone', 'length', 'max'=>255),
			array('value', 'length', 'max'=>50),
			
			//required
			array('parameter, zone, value', 'required'),
			
			//safe
			array('active,required','safe'),
			
			//The following rule is used by search().
			array('parameter,zone,value,active,required', 'safe', 'on'=>'search'),
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
			'zones'=>array(self::HAS_MANY,'Zones',array('zone'=>'parent_zone_name')),
			'request_parameters'=>array(self::BELONGS_TO,'WaterRequestParameters','parameter'),
		);
	}
	
	/**
	 * @return array query criteria
	 */
	public function scopes()
    {
        return array(
            'active_parameters'=>array(
                'condition'=>'active=true ',//ORDER BY name',
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'parameter' => 'Request Parameter',
			'zone' => 'Zone',
			'value' => 'Value',
			'active' => 'Active',
			'required' => 'Require',
			'request_parameters.name' => 'Request Parameter',
			'request_parameters.description' => 'Description',
			'request_parameters.measurement_unit' => 'Measurement unit',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	    $criteria=new CDbCriteria;
		
		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('required',$this->required,true);
	    return new CActiveDataProvider(get_class($this), array(
	        'criteria'=> $criteria,
	        'sort'=>array(
	            'defaultOrder'=>'parameter ASC, zone ASC',
	        ),
	        'pagination'=>array(
	            'pageSize'=>10
	        ),
	    ));
	}
}