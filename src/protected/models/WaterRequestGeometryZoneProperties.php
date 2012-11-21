<?php

/**
 * This is the model class for table "water_request_geometry_zone_properties".
 *
 * The followings are the available columns in table 'water_request_geometry_zone_properties':
 * @property integer $id
 * @property integer $geometry_zone
 * @property string $parameter
 * @property string $value
 */
class WaterRequestGeometryZoneProperties extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterRequestGeometryZoneProperties the static model class
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
		return 'water_request_geometry_zone_properties';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('geometry_zone, parameter, value', 'required'),
			array('geometry_zone', 'numerical', 'integerOnly'=>true),
			array('parameter, value', 'length', 'max'=>255),
			array('value', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, geometry_zone, parameter, value', 'safe', 'on'=>'search'),
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
			'zone'=>array(self::BELONGS_TO, 'WaterRequestGeometryZones', 'geometry_zone'),
			'wr_parameter'=>array(self::BELONGS_TO, 'WaterRequestParameters', 'parameter'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'geometry_zone' => 'Geometry Zone',
			'parameter' => 'Parameter',
			'value' => 'Value',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($merge=null)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('geometry_zone',$this->geometry_zone);
		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('value',$this->value,true);

		if($merge!==null)
             $criteria->mergeWith($merge);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * This parameter description
	 * @return string
	 */
	public function getDescription() {
		$param = WaterRequestParameters::model()->findByPk($this->parameter);
		if ($param)
			return $param->description;
		return '';
	}
	
	/**
	 * (non-PHPdoc)
	 * Updates PE and WaterDemand
	 * @see CActiveRecord::afterSave()
	 */
	public function afterSave() {
		if (($this->use4ae) || (stripos($this->zone->zone->formula->formula,$this->parameter)!==false)) {
			
			$zone = $this->zone;
			$zone->updatePEAndWD();
			$zone->save();
		}
		return parent::afterSave();
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