<?php

/**
 * This is the model class for table "water_demand_formulas".
 *
 * The followings are the available columns in table 'water_demand_formulas'
 * @property string $zone the zone identifier
 * @property string $formula the expression that allows to calculate the cost of a water request running on a particular area
 */
class WaterRequestFormulas extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return WaterRequestFormulas the static model class
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
		return 'water_demand_formulas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('zone','unique'),
			
			//max_length
			array('zone', 'length', 'max'=>255),
			array('formula', 'length', 'max'=>50),
			
			//required
			array('zone, formula', 'required'),
			
			//The following rule is used by search().
			array('zone,formula', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zone' => 'Zone',
			'formula' => 'Flow calculation formula',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	    $criteria=new CDbCriteria;
		
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('formula',$this->formula,true);
	    return new CActiveDataProvider(get_class($this), array(
	        'criteria'=> $criteria,
	        'sort'=>array(
	            'defaultOrder'=>'zone ASC',
	        ),
	        'pagination'=>array(
	            'pageSize'=>10
	        ),
	    ));
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