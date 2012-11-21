<?php

/**
 * This is the model class for table "water_request_geometry_zones".
 *
 * The followings are the available columns in table 'water_request_geometry_zones':
 * @property integer $id
 * @property integer $wr_geometry_id
 * @property string $zone_name
 * @property double $pe
 * @property double $water_demand
 */
class WaterRequestGeometryZones extends CActiveRecord
{
	
	/**
	 * afterConstruct
	 */
	
	protected function afterConstruct()
	{
		$this->pe = 0;
		$this->water_demand = 0;
		parent::afterConstruct();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterRequestGeometryZones the static model class
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
		return 'water_request_geometry_zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wr_geometry_id, zone_name', 'required'),
			array('wr_geometry_id', 'numerical', 'integerOnly'=>true),
			array('pe, water_demand', 'numerical'),
			array('zone_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wr_geometry_id, zone_name, pe, water_demand', 'safe', 'on'=>'search'),
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
			'geometry'=>array(self::BELONGS_TO, 'WaterRequestGeometries', 'wr_geometry_id'),
			'properties'=>array(self::HAS_MANY, 'WaterRequestGeometryZoneProperties', 'geometry_zone'),
			'zone'=>array(self::BELONGS_TO, 'Zones', 'zone_name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'wr_geometry_id' => 'Wr Geometry',
			'zone_name' => 'Category',
			'pe' => 'Population Equivalent',
			'water_demand' => 'Water Demand',
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
		$criteria->compare('wr_geometry_id',$this->wr_geometry_id);
		$criteria->compare('zone_name',$this->zone,true);
		$criteria->compare('pe',$this->pe);
		$criteria->compare('water_demand',$this->water_demand);
		
		if($merge!==null)
             $criteria->mergeWith($merge);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function updatePEAndWD($parameter=null, $value=null, $geom=null, &$info=null)
	{
		//retrieve city_state
		$g = isset($geom)?$geom:$this->wr_geometry_id;
		$service_area = Geometry::Get_Service_Area_Detailed($g);
		$city_state = Geometry::Get_City_State($g);
		if ($city_state == null) {
			Yii::log('Comune non trovato. $g='.$g , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
			if (isset($info)) {
				$info['city'] = '--';
				$info['service_area'] = '--';
				$info['water_demand'] = -1;
				$info['margin'] = -1;
				$info['maximum_water_supply'] = -1;
				$info['scenari'] = null;
			}
			return;
		}
		
		$water_supply = WaterSupply::model()->find('lower(city_state)=:city_state',array(':city_state'=>strtolower($city_state)));
		if(!$water_supply){
			Yii::log('Manca la WaterSupply. $city_state='.$city_state , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
			return;
		}
		$dg = $water_supply->daily_maximum_water_supply;  
		$da = $water_supply->yearly_average_water_supply;
		
		if ((!isset($parameter)) || (!isset($value))) {
			$p = null;
			$v = null;
			foreach($this->properties as $property) {
				if ($property->use4ae) {
					$p = $property->parameter;
					$v = $property->value;
					break;		
				}
			}
		}
		else {
			$p = $parameter;
			$v = $value;
		}
		
		if ($p===null) {
			$this->pe = 0;
		} 
		else {
			//calculate PE
			$zone = $this->zone_name;
			
			while ($zone!=null) {
				$conversion_index = ZonesWaterRequestParameters::model()->active_parameters()->find('parameter=:parameter AND zone=:zone',array(':parameter'=>$p,':zone'=>$zone));
				if ($conversion_index)
					break;
				$zone = Zones::parentZone($zone);
			}
			
			if (strcmp($p,$conversion_index->parameter)==0) {
				if (is_numeric($conversion_index->value)) {
					$val = $conversion_index->value;
					$this->pe = $v * $val;
				}
				else {
					$val = str_ireplace(array('dg','da'),array($dg, $da),$conversion_index->value); //TODO: mettere nel config il valore delle stringe dg e da
					try {
						$val = Math::safe_eval($val);
						$ret = $v * $val;
						if (is_numeric($ret))
							$this->pe = $ret;
						else
							$this->pe = -1;
					}
					catch(Exception $e){
						$this->pe = 0;
						Yii::log('PE Error. $conversion_index='.$conversion_index->parameter.'|'.$conversion_index->value , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
					}
				}
			}
			else {
				$this->pe = 0;
			}
		}
		
		$formula = $this->zone->formula;
		 
		$fformula = $formula->formula;
		
		/*
		if (stripos($formula->formula,Yii::app()->params['formulas']['pe'])!==false) {
			$fformula = str_ireplace(Yii::app()->params['formulas']['pe'],$this->pe,$fformula);
		}
		
		if (stripos($formula->formula,Yii::app()->params['formulas']['dg'])!==false) {
			$fformula = str_ireplace(Yii::app()->params['formulas']['dg'],$dg,$fformula);
		}
		
		if (stripos($formula->formula,Yii::app()->params['formulas']['da'])!==false) {
			$fformula = str_ireplace(Yii::app()->params['formulas']['da'],$da,$fformula);
		}*/
		
		foreach ($this->properties() as $property) {
			if (stripos($formula->formula,$property->parameter)!==false) {
				$fformula = str_ireplace($property->parameter,$property->value,$fformula);
			}
		}
		
		if (stripos($formula->formula,Yii::app()->params['formulas']['da'])!==false) {
			$fformula = str_ireplace(Yii::app()->params['formulas']['da'],$da,$fformula);
		}
		
		if (stripos($formula->formula,Yii::app()->params['formulas']['dg'])!==false) {
			$fformula = str_ireplace(Yii::app()->params['formulas']['dg'],$dg,$fformula);
		}
		
		$fformula_with_pe = null;
		if (stripos($formula->formula,Yii::app()->params['formulas']['pe'])!==false) {
			$fformula_with_pe = $fformula;
			$fformula = str_ireplace(Yii::app()->params['formulas']['pe'],$this->pe,$fformula);
		}
		
		try {
			$ret = Math::safe_eval($fformula);
			if (is_numeric($ret))
				$this->water_demand = $ret;
			else
				$this->water_demand = -1;
		}
		catch(Exception $e){
			$this->water_demand = 0;
			Yii::log('WD Error. $fformula='.$fformula, CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
		}
		
		if (isset($info)) {
			$info['city'] = ucwords(strtolower($city_state));
			$info['water_demand'] = Math::wd_round($this->water_demand).' '.Yii::app()->params['water_demand_unit'];
			
			if($service_area == null){
				$info['service_area'] = '--';
				$operative_margin = DummyOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($city_state)));
				if (count($operative_margin)==0) {
					Yii::log('Manca il margine operativo del comune. area='.$city_state , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
					return;
				}
			}else
			{
				//var_dump($service_area);
				$info['service_area'] = ucwords(strtolower($service_area['desc_area'])); /*TODO: cambiare il $service_area*/
				$operative_margin = DummySAOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($service_area['area'])));
				if (count($operative_margin)==0) {
					Yii::log('Manca il margine operativo dell\'area di servizio. area='.$city_state , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
					return;
				}
			}
			$actual_margin = null;
			foreach($operative_margin as $op) {
				if ($op->scenario==null) {
					$actual_margin = Math::margin_round($op->margin);
					break;
				}
			}
			if ($actual_margin == null) {
				Yii::log('Manca il margine operativo attuale. area='.$city_state , CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
				$actual_margin = 0;
			}
			
			if ($this->water_demand > $actual_margin)
				$info['margin']=-1;
			else if ($this->water_demand > ($actual_margin - ($actual_margin * (int)Yii::app()->params['water_demand_range'] / 100)) )
				$info['margin']=0;
			else
				$info['margin']=1;
			
			$info['maximum_water_supply'] = Math::margin_round($actual_margin). ' '.Yii::app()->params['water_demand_unit'];
			
			if ($fformula_with_pe!= null) {
				$info['max_parameter'] = array();
				$info['max_parameter']['name'] = $p;
				$fformula_with_pe = str_ireplace(Yii::app()->params['formulas']['pe'],1/$actual_margin,$fformula_with_pe);
				try {
					$ret = Math::safe_eval($fformula_with_pe);
					if (is_numeric($ret))
						$max_pe = 1/$ret;
					else
						$max_pe = -1;
				}
				catch(Exception $e){
					$max_pe = -1;
					Yii::log('MAX PE Error. $fformula_with_pe='.$fformula_with_pe, CLogger::LEVEL_INFO, 'updatePEAndWD');  // DEBUG
				}
				if ($max_pe > 0)
					$info['max_parameter']['value'] = $max_pe / $val;
				else
					$info['max_parameter']['value'] = -1;	
			}
			
			$info['scenari'] = array();
			foreach($operative_margin as $op) {
				if ($op->scenario==null)
					continue;
				$item=array();
				$item['scenario']=$op->scenario;
				$item['maximum_water_supply'] = Math::margin_round($op->margin). ' '.Yii::app()->params['water_demand_unit'];
				
				if ($this->water_demand > $op->margin)
					$item['margin'] = -1;
				else if ($this->water_demand > ($op->margin - ($op->margin * (int)Yii::app()->params['water_demand_range'] / 100)) )
					$item['margin'] = 0;
				else
					$item['margin'] = 1;
				array_push($info['scenari'],$item);
			}
		}
		return;
	}
	
	public function calculatePE($parameter, $value, $geom=null)
	{
		$zone = $this->zone_name;
		//var_dump($this->properties);
		//$properties = $this->properties->find('geometry_zone=:geometry_zone',array(':geometry_zone'=>$this->id));
		while ($zone!=null) {
			//$parameter = $properties->parameter;
			$conversion_index = ZonesWaterRequestParameters::model()->active_parameters()->find('parameter=:parameter AND zone=:zone',array(':parameter'=>$parameter,':zone'=>$zone));
			if ($conversion_index)
				break;
			$zone = Zones::parentZone($zone);
		}
		//Yii::log($conversion_index->zone.' = '.$conversion_index->value ,CLogger::LEVEL_INFO,"WRGZones->CalculatePE()");
		// Se trovo che la PE si calcola senza bisogno di DG o DA, restituisco direttamente il numero
		if (is_numeric($conversion_index->value))
			return $value * $conversion_index->value;
		
		//Yii::log('ctype_digit='.(ctype_digit($geom_id)?'true':'false').' geom_id='.$geom_id , CLogger::LEVEL_INFO, 'calculatePE');  // DEBUG
		if ($geom == null)
			$comuni = Geometry::Get_City_State($this->wr_geometry_id);
		else
			$comuni = Geometry::Get_City_State_ByWKT($geom);
		/*	
		if(!ctype_digit($geom)){
			$comuni = Geometry::Get_City_State_ByWKT($geom);
		}else{
			$comuni = Geometry::Get_City_State($this->wr_geometry_id);
		}*/
		if(count($comuni)){
			$city_state = $comuni[0]['nome'];
		}
		else  
		{
			Yii::log('non trovo il comune. geom_id='.$geom , CLogger::LEVEL_INFO, 'calculatePE');  // DEBUG
			$city_state = 'Pisa';
		}			
			
		$water_supply = WaterSupply::model()->find('lower(city_state)=:city_state',array(':city_state'=>strtolower($city_state)));
		if(!$water_supply){
			Yii::log('Manca la WaterSupply. $city_state='.$city_state , CLogger::LEVEL_INFO, 'calculatePE');  // DEBUG
			return -1;
		}
		$dg = $water_supply->daily_maximum_water_supply;  
		$da = $water_supply->yearly_average_water_supply;
		$val = str_ireplace(array('dg','da'),array($dg, $da),$conversion_index->value); //TODO: mettere nel config il valore delle stringe dg e da
		$ret = $value * Math::safe_eval($val);

		if (is_numeric($ret))
			return $ret;
		return -1;
	}
	
	public function calculateWaterDemand($geom=null)
	{
		$zone = $this->zone_name;
		while ($zone!=null) {
			$formula = WaterRequestFormulas::model()->find('zone=:zone',array(':zone'=>$zone));
			if ($formula)
				break;
			$zone = Zones::parentZone($zone);
		}
		//Yii::log('ctype_digit='.print_r(ctype_digit($geom_id),true).' geom_id='.$geom_id , CLogger::LEVEL_INFO, 'calculateWaterDemand');  // DEBUG
		if ($geom == null)
			$comuni = Geometry::Get_City_State($this->wr_geometry_id);
		else
			$comuni = Geometry::Get_City_State_ByWKT($geom);
		/*
		if(!ctype_digit($geom_id)){
			$comuni = Geometry::Get_City_State_ByWKT($geom_id);
		}else{
			$comuni = Geometry::Get_City_State($this->wr_geometry_id);
		}*/
		if(count($comuni)){
			$city_state = $comuni[0]['nome'];
		}
		else  
		{
			Yii::log('non trovo il comune. geom_id='.$geom , CLogger::LEVEL_INFO, 'calculateWaterDemand');  // DEBUG
			$city_state = 'Pisa';
		}			
		
		$water_supply = WaterSupply::model()->find('lower(city_state)=:city_state',array(':city_state'=>strtolower($city_state)));
		if(!$water_supply){
			Yii::log('Manca la WaterSupply. $city_state='.$city_state , CLogger::LEVEL_INFO, 'calculateWaterDemand');  // DEBUG
			return -1;
		}
		$dg = $water_supply->daily_maximum_water_supply;  
		$da = $water_supply->yearly_average_water_supply;
		$fformula = str_ireplace(array('ae','dg','da'),array($this->pe, $dg, $da ),$formula->formula); //TODO: mettere nel config il valore delle stringe ae, dg e da
		$ret = Math::safe_eval($fformula);
		if (is_numeric($ret))
			return $ret;
		return -1;
	}

	public function getZoneDescription()
	{
			$z = $this->zone;
			if ($z)
				return $z->description;
			return 'Unknown';
	}

	public function beforeSave()
	{
		if(!$this->isNewRecord) {
			//retrieve old water_demand value
			$old = WaterRequestGeometryZones::model()->findByPk($this->id);
			if ($old) {
				$old_wd = (float)$old->water_demand;
				//check if water demand has been changed
				if ($old_wd != $this->water_demand) {
					//update geometry water demand and water_request water demand
					Yii::log('OLD WD: '.$old_wd , CLogger::LEVEL_INFO, 'beforeSave');  // DEBUG
					Yii::log('NEW WD: '.$this->water_demand , CLogger::LEVEL_INFO, 'beforeSave');  // DEBUG
					Yii::log('GEOM_WATER_DEMAND: '.$this->geometry->geom_water_demand , CLogger::LEVEL_INFO, 'beforeSave');  // DEBUG
					$this->geometry->geom_water_demand-=$old_wd;
					$this->geometry->geom_water_demand+=$this->water_demand;
					Yii::log('UPDATED GEOM_WATER_DEMAND: '.$this->geometry->geom_water_demand , CLogger::LEVEL_INFO, 'beforeSave');  // DEBUG
					$this->geometry->save();
					$this->geometry->wr->total_water_demand-=$old_wd;
					$this->geometry->wr->total_water_demand+=$this->water_demand;
					$this->geometry->wr->save();
				}
			}
		}
		Yii::log('BEFORE', CLogger::LEVEL_INFO, 'beforeSave');  // DEBUG
		return parent::beforeSave();
	}

	public function beforeDelete()
	{
		$this->geometry->geom_water_demand-=$this->water_demand;
		$this->geometry->save();
		$this->geometry->wr->total_water_demand-=$this->water_demand;
		$this->geometry->wr->save();
		return parent::beforeDelete();
	}
	/*
	public function afterSave()
	{
		//update geometry water demand and water_request water demand
		Yii::log('GEOM_WATER_DEMAND: '.$this->geometry->geom_water_demand , CLogger::LEVEL_INFO, 'afterSave');  // DEBUG
		$this->geometry->geom_water_demand+=$this->water_demand;
		Yii::log('UPDATED GEOM_WATER_DEMAND: '.$this->geometry->geom_water_demand , CLogger::LEVEL_INFO, 'afterSave');  // DEBUG
		$this->geometry->save();
		$this->geometry->wr->total_water_demand+=$this->water_demand;
		$this->geometry->wr->save();
		return parent::afterSave();
	}*/
	
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