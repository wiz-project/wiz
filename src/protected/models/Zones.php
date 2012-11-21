<?php

/**
 * This is the model class for table "zones".
 *
 * The followings are the available columns in table 'zones'
 * @property string $name the zone identifier
 * @property string $description the description of area 
 * @property boolean $active
 * @property boolean $searchable
 * @property string $parent_zone_name the parent zone identifier
 */
class Zones extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Zones the static model class
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
		return 'zones';
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
			array('name,parent_zone_name', 'length', 'max'=>255),
			array('description', 'length', 'max'=>500),
			
			//required
			array('name, description, parent_zone_name', 'required'),
			
			//safe
			array('active, searchable', 'safe'),
			
			//The following rule is used by search().
			array('name,description,active,searchable,parent_zone_name', 'safe', 'on'=>'search'),
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
			//'formula'=>array(self::BELONGS_TO,'WaterRequestFormulas',array('parent_zone_name'=>'zone')),
			'_formula'=>array(self::HAS_ONE,'WaterRequestFormulas','zone'),
			'zone_request_parameters'=>array(self::HAS_MANY,'ZoneWaterRequestParameters',array('parent_zone_name'=>'zone')),
			'request_parameters'=>array(self::HAS_MANY,'WaterRequestParameters',array('parameter'=>'name'),'through'=>'zone_request_parameters','joinType'=>'INNER JOIN'),
			'parent_zone'=>array(self::BELONGS_TO,'Zones', 'parent_zone_name')
		);
	}

	/**
	 * @return array query criteria
	 */
	public function scopes()
    {
        return array(
            'active_zones'=>array(
                'condition'=>'active=true AND searchable=true AND parent_zone_name IS NOT NULL ORDER BY name',
            ),
			'all_zones'=>array(
                'condition'=>'searchable=true ORDER BY name',
            ),
        );
    }
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Zone',
			'description' => 'Description',
			'active' => 'Active',
			'searchable' => 'Searchable',
			'parent_zone_name' => 'Macro Zone'
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
		$criteria->compare('parent_zone_name',$this->parent_zone_name,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('searchable',$this->searchable);
	    return new CActiveDataProvider(get_class($this), array(
	        'criteria'=> $criteria,
	        'sort'=>array(
	            'defaultOrder'=>'name ASC, parent_zone_name ASC',
	        ),
	        'pagination'=>array(
	            'pageSize'=>10
	        ),
	    ));
	}
	
	/**
	 * Retrieves the list of available zones
	 * @return Zones models
	 */
	public static function zonesList($phase)
	{
		$zones = Zones::model()->active_zones()->findAll();
		if ($phase == null)
			return $zones;
		if ($phase == 3) {
			$ret = array();
			foreach($zones as $zone) {
				if (!$zone->hasChild()) {
					if (isset($zone->parent_zone) && ($zone->parent_zone->name!=null))
						if ($zone->parent_zone->parent_zone_name!=null)
							$ret = array_merge($ret, array($zone->name => $zone->parent_zone->description.' » '.$zone->description));
						else 
							$ret = array_merge($ret, array($zone->name => $zone->description));
				}	
			}
		}
		else {
			$ret = array();
			foreach($zones as $zone) {
				if (isset($zone->parent_zone) && ($zone->parent_zone->name!=null))
					if ($zone->parent_zone->parent_zone_name!=null)
						$ret = array_merge($ret, array($zone->name => $zone->parent_zone->description.' » '.$zone->description));
					else 
						$ret = array_merge($ret, array($zone->name => $zone->description));
			}
		}
		return $ret;
	}
	
	/**
	 * Retrieves the list of all zones
	 * @return Zones models
	 */
	public static function zonesListAll()
	{
		return Zones::model()->all_zones()->findAll();
	}
	
	/**
	 * For a given zone, returns the parent
	 * @param string $z the zone identifier
	 * @return string the parent zone
	 */
	public static function parentZone($z) {
		$zone = Zones::model()->findByPk($z);
		if ($zone)
			$parent_zone = $zone->parent_zone_name;
			if ($parent_zone)
				return $parent_zone;
			return null;
	}
	
	/**
	 * Verify the existence of children associated with the area.
	 * Returns true if the area has children.
	 */
	public function hasChild() {
		$zones = Zones::model()->findByAttributes(
			array(
				'parent_zone_name'=>$this->name,
				
			));
		if (count($zones))
			return true;
		return false;
	}
	
	/**
	 * Returns the formula associated with the area
	 */
	public function getFormula() {
		$formula = null;	
		$zone = $this->name;
		while ($zone!=null) {
			$formula = WaterRequestFormulas::model()->find('zone=:zone',array(':zone'=>$zone));
			if ($formula)
				break;
			$zone = Zones::parentZone($zone);
		}
		return $formula;
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

?>