<?php

/**
 * This is the model class for table "water_request_geometries".
 *
 * The followings are the available columns in table 'water_request_geometries':
 * @property integer $id
 * @property integer $wr_id
 * @property integer $altitude
 * @property double precision $geom_water_demand
 * @property string $geom
 * @property string $name
 */
class WaterRequestGeometries extends CActiveRecord
{

	/**
	 * afterConstruct
	 */
	
	protected function afterConstruct()
	{
		$this->geom_water_demand = 0;
		parent::afterConstruct();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterRequestGeometries the static model class
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
		return 'water_request_geometries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wr_id', 'required'),
			array('wr_id', 'numerical', 'integerOnly'=>true),
			array('geom', 'safe'),
			array('name', 'type', 'type'=>'string'),
			//array('name', 'type', 'type'=>'string'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wr_id, geom', 'safe', 'on'=>'search'),
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
			'wr'=>array(self::BELONGS_TO, 'WaterRequests', 'wr_id'),
			'zones'=>array(self::HAS_MANY, 'WaterRequestGeometryZones', 'wr_geometry_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'wr_id' => 'Wr',
			'geom' => 'Geom',
			'name' => 'Name',
			'altitude' => 'Elevation',
			'centroid' => 'Centroid'
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
		$criteria->compare('wr_id',$this->wr_id);
		$criteria->compare('geom',$this->geom,true);
		$criteria->compare('altitude',$this->elevation,true);

		
		if($merge!==null)
             $criteria->mergeWith($merge);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Return the CityState of the current geometry.
	 * @return String or Null.
	 */
	public function getCity_state() {

		$city = Geometry::Get_City_State($this->id);
		/*
		if ($city)
			if (isset($city[0]['nome']))
				return $city[0]['nome'];
		return '';*/
		if ($city)
			return $city;
		return '';
	}

	/**
	 * Return the ServiceArea details of the current geometry.
	 * @return Array or Null.
	 */
	public function getService_area() {

		return Geometry::Get_Service_Area($this->id);
		
	}
	/**
	 * Return the Centroid of the current geometry.
	 * @param integer $srid Specify the srid for the output
	 * @return Array with keys 'X' and 'Y' or Null.
	 */
	public function getCentroid($srid=null){
		
		if(!$this->id)  // WaterRequestGeometries not initialized
			return null;
			
		$connection=Yii::app()->db;
		if($srid===null)
			$sql = 'SELECT ST_X(ST_Centroid(geom)) as X, ST_Y(ST_Centroid(geom)) as Y FROM '.$this->tableName().' WHERE id='.$this->id.';';
		else
			$sql = 'SELECT ST_X(ST_Centroid(ST_Transform(geom,'.$srid.'))) as X, ST_Y(ST_Centroid(ST_Transform(geom,'.$srid.'))) as Y FROM '.$this->tableName().' WHERE id='.$this->id.';';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows, true) , CLogger::LEVEL_INFO, 'getCentroid');  // DEBUG
		if(count($rows))
			return $rows[0];
		else
			return null;
		
	}
	
	/**
	 * Return the Bounding box of the current geometry centroid.
	 * Used to create wms queries.
	 * @return Array with keys 'xmin','ymin','xma' and 'ymax' or Null.
	 */
	public function getCentroid_BBox(){
		
		if(!$this->id)  // WaterRequestGeometries not initialized
			return null;
			
		$centroid = $this->getCentroid();
			
		if($centroid) {
			//var_dump($centroid);
			return array('xmin'=>$centroid['x']-0.000001,
						 'ymin'=>$centroid['y']-0.000001,
						 'xmax'=>$centroid['x']+0.000001,
						 'ymax'=>$centroid['y']+0.000001,
			);
		}
		else
			return null;
		
	}

	/**
	 * Return the Area of the current geometry or null if not initialized.
	 * The Area is in SRID units (meters for EPSG:3003)
	 * @return String or Null.
	 */
	public function getSup() {
		if(!$this->id)  // WaterRequestGeometries not initialized
			return null;
			
		$connection=Yii::app()->db;
		$sql = 'SELECT ST_Area(transform(geom, 3003)) as Area FROM '.$this->tableName().' WHERE id='.$this->id.';';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		if(count($rows))
			return $rows[0]['area'];
		else
			return null;  // Caso raro
	}

	public function getElevation() {
		
		if($this->altitude !== null)
			return $this->altitude;
		
		$cansave = false;
		
		$service = 'WMS';
		$request = 'GetFeatureInfo';
		$style='';
		$width=3;
		$height=3;
		$bbox = $this->getCentroid_BBox();
		$boundy_box = $bbox ? ''.$bbox['xmin'].','.$bbox['ymin'].','.$bbox['xmax'].','.$bbox['ymax'] : '';
		$x=0;
		$y=0;
		$info_format = 'application/vnd.ogc.gml';
		$elevation_url = Yii::app()->params['geoserver']['protocol'].
							Yii::app()->params['geoserver']['ip'].
							':'.
							Yii::app()->params['geoserver']['port'].
							Yii::app()->params['geoserver']['path'].
							Yii::app()->params['geoserver']['wms'].
							'/'.
							'?service='.$service.
							'&version='.Yii::app()->params['geoserver']['version'].
							'&request='.$request.
							'&layers='.Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['layer_dem'].
							'&query_layers='.Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['layer_dem'].
							'&style='.$style.
							'&bbox='.$boundy_box.
							'&width='.$width.
							'&height='.$height.
							'&srs='.Yii::app()->params['geoserver']['default_srs'].
							'&x='.$x.
							'&y='.$y.
							'&info_format='.$info_format;
		try {
			$elevation_xml = @simplexml_load_file($elevation_url);
			if($elevation_xml===false){
				$elevation = "DEM Offline";
			}else{
				$cansave = true;
				$elevation = $elevation_xml->children('gml', true)->{'featureMember'}[0]->children('acque', true)->{'DEM'}->{'digital-elevation'};
			}
		}
		catch(Exception $e) {
			Yii::log('Elevation error. URL:'.$elevation_url.' XML:'.$elevation_xml->asXML() , CLogger::LEVEL_WARNING, 'actionEpanet');  // DEBUG
			$elevation = -1;
		}
		
		if($cansave){
			$this->altitude = $elevation;
			if(!$this->save())
				Yii::log('Errore nel salvataggio con $elevation='.$elevation, CLogger::LEVEL_INFO,'altitude_saving');
		}
		
		return $elevation;
	}
	
	/**
	 * @return string This geometry image link to wms
	 */
	public function imageSRC() {
		$service = 'WMS';
		$request = 'GetMap';
		$style='';
		$width=800;
		$height=500;
		$format='image/jpeg';
		$param='id';
		$buffer = 0.0005;
		$bbox = $this->getBBox();
		$boundy_box = $bbox ? ''.($bbox['xmin']-$buffer).','.($bbox['ymin']-$buffer).','.($bbox['xmax']+$buffer).','.($bbox['ymax']+$buffer) : '';
		
		$ret = Yii::app()->params['geoserver']['protocol'].
				Yii::app()->params['geoserver']['ip'].
				':'.
				Yii::app()->params['geoserver']['port'].
				Yii::app()->params['geoserver']['path'].
				'/'.
				Yii::app()->params['geoserver']['workspace'].
				Yii::app()->params['geoserver']['wms'].
				'?service='.$service.
				'&version='.Yii::app()->params['geoserver']['version'].
				'&request='.$request.
				'&layers='.	Yii::app()->params['geoserver']['workspace'].':'.'pdf_idrst10k'.
						 	','.
						 	Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['pdf_geoms'].
				'&style='.$style.
				'&bbox='.$boundy_box.
				'&width='.$width.
				'&height='.$height.
				'&srs='.Yii::app()->params['geoserver']['default_srs'].
				'&format='.$format.
				'&viewparams='.$param.':'.$this->id;
				//Yii::log($ret, CLogger::LEVEL_INFO, "imageSRC");  // DEBUG
		return $ret;
		
	}
	
	/**
	 * @return string an html img tag of this geometry
	 */
	public function imageTag(){
		return '<img src="'.$this->imageSRC().'" />';
	}

	public function getBBox(){
		
		if(!$this->id)  // WaterRequestGeometries not initialized
			return null;
			
		$connection=Yii::app()->db;
		$sql = 'SELECT ST_Xmin(ST_Box2d(geom)) as Xmin, ST_Ymin(ST_Box2d(geom)) as Ymin, ST_Xmax(ST_Box2d(geom)) as Xmax, ST_Ymax(ST_Box2d(geom)) as Ymax FROM '.$this->tableName().' WHERE id='.$this->id.';';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		if(count($rows))
			return $rows[0];
		else
			return null;
		
	}

	/*
	public function probable() {
		$ret = array();
		$city_state = Geometry::Get_City_State($this->id);
		
		if ($city_state == null) {
			$ret['city'] = '--';
			$ret['margin'] = -1;
			$ret['maximum_water_supply'] = -1;
			return $ret;
		}
		$ret['city'] = ucwords(strtolower($city_state));
		
		$water_supply = WaterSupply::model()->find('lower(city_state)=:city_state',array(':city_state'=>strtolower($city_state)));
		if(!$water_supply){
			Yii::log('Manca la WaterSupply. $city_state='.$city_state , CLogger::LEVEL_INFO, 'calculatePE');  // DEBUG
			$ret['margin'] = -1;
			$ret['maximum_water_supply'] = -1;
			return $ret;
		}
		$dg = $water_supply->daily_maximum_water_supply;  
		$da = $water_supply->yearly_average_water_supply;
		$ret['maximum_water_supply'] = $dg;
		if ($this->geom_water_demand > $dg)
			$ret['margin'] = -1;
		else if ($this->geom_water_demand > $dg - (int)Yii::app()->params['water_demand_range'])
			$ret['margin'] = 0;
		else
			$ret['margin'] = 1;
		return $ret;
	}*/
	
	public function probable() {
		return WaterRequestGeometries::feasibilityCheck($this->id,$this->geom_water_demand);
	}

	public static function feasibilityCheck($geom_id,$wd) {
		//Yii::log('geom_id= '.print_r($geom_id, true).' wd= '.print_r($wd, true), CLogger::LEVEL_INFO, 'feasibilitycheck()');  // DEBUG
		
		$ret = array();
		$service_area=Geometry::Get_Service_Area_Detailed($geom_id);
		$city_state=Geometry::Get_City_State($geom_id);
		//Yii::log('city_state= '.print_r($city_state, true).' wd= '.print_r($wd, true), CLogger::LEVEL_INFO, 'feasibilitycheck()');  // DEBUG
		if (($city_state == null) || ($wd == null)) {
				$ret['sarea'] = null;
				$ret['city'] = '--';
				$ret['scenari'] = null;
				$ret['margin'] = -1;
				$ret['maximum_water_supply'] = -1;
				return $ret;
			}
		$ret['city'] = ucwords(strtolower($city_state));  // uso city_state ma potrei usare service_area->desc_area se Get_Service_Area ritornasse un array
		
		if(!$service_area){
			$ret['sarea'] = null;
			$water_supply = DummyOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($city_state)), array('limit'=>3));
			if(!$water_supply){
				Yii::log('Cannot find Operative Margin for $city_state='.$city_state , CLogger::LEVEL_INFO, 'feasibilityCheck');  // DEBUG
				$ret['scenari'] = null;
				$ret['margin'] = -1;
				$ret['maximum_water_supply'] = -1;
				return $ret;
			}
		}else
		{	
			$ret['sarea'] = $service_area['desc_area'];
			$water_supply = DummySAOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($service_area['area'])), array('limit'=>3));
		}
		$ret['scenari'] = array();
		foreach($water_supply as $ws) {
			//Yii::log(print_r($ws->attributes, true), CLogger::LEVEL_INFO, 'foreach');  // DEBUG
			$operative_margin = $ws->margin;  
			if ($ws->scenario==null)
			{
				$ret['maximum_water_supply'] = Math::wd_round($operative_margin);
				if ($wd > $operative_margin)
					$ret['margin'] = -1;
				else if ($wd > $operative_margin - (int)Yii::app()->params['water_demand_range'])
					$ret['margin'] = 0;
				else
					$ret['margin'] = 1;
			}
			else {
				$item=array();
				$item['scenario']=$ws->scenario;
				$item['maximum_water_supply'] = Math::wd_round($operative_margin);
				if ($wd > $operative_margin)
					$item['margin'] = -1;
				else if ($wd > $operative_margin - (int)Yii::app()->params['water_demand_range'])
					$item['margin'] = 0;
				else
					$item['margin'] = 1;
				array_push($ret['scenari'],$item);
			}
		}
		return $ret;
	}
	
	protected function beforeDelete()
	{
		
		WaterRequestGeometryZones::model()->deleteAll('wr_geometry_id = :wr_geometry_id', array(':wr_geometry_id' => $this->id));
		return parent::beforeDelete();
	}

	public static function save_geom($wkt, $wr_id, $proj=3003){
		$model=new WaterRequestGeometries;
		$model->wr_id=$wr_id;
		$model->name = 'uploaded';
		$model->geom=new CDbExpression(Geometry::Transform(Geometry::ST_GeomFromText($wkt,$proj)));
		return array('result'=>$model->save(), 'newid'=>$model->id);
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