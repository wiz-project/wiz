<?php

/**
 * This is the model class for table "water_requests".
 *
 * The followings are the available columns in table 'water_requests':
 * @property integer $id
 * @property string $timestamp
 * @property string $username
 * @property string $project
 * @property string $description
 * @property string $status
 * @property integer $phase
 * @property integer $parent_phase
 * @property double $total_water_demand
 * @property double $effective_water_demand
 * @property string $note
 * @property double $cost
 * @property string $file_link
 * @property date expiration_date
 */
class WaterRequests extends CActiveRecord
{
	const TEMP_STATUS = 'temp';
	const SAVED_STATUS = 'saved';
	const SUBMITTED_STATUS = 'submitted';
	const CANCELLED_STATUS = 'cancelled';
	const APPROVED_STATUS = 'approved';
	const REJECTED_STATUS = 'rejected';
	const IN_FUTURE_STATUS = 'in_future';
	const CONFIRMED_STATUS = 'confirmed';
	const REFUSED_STATUS = 'refused';
	const IN_PROGRESS_STATUS = 'in_progress';
	const TIMEOUT_STATUS = 'timeout';
	const COMPLETED_STATUS = 'completed';
	const WORKFLOW = 'swWaterRequests';
	
	
	
	/* TODO: Salvandolo nel DB può diventare un segnale del fatto che per questa Water Request
	 * 		è già stato caricato uno shape file. 
	 */
	public $shpfile;
	public $shxfile;
	public $dbffile;
	public $fileproj;
	public $file;
	
	/**
	 * afterConstruct
	 * Sets a default project name, timestamp and username
	 */
	
	protected function afterConstruct()
	{
		$this->project = 'Project '.date(Yii::app()->params['dateFormat']);
		$this->username = Yii::app()->user->id;
		$this->timestamp = date(Yii::app()->params['dateTimeFormatDB']);
		$this->total_water_demand = 0;
		$this->effective_water_demand = 0;
		$this->status = $this->swGetStatus()->toString();
		parent::afterConstruct();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterRequests the static model class
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
		return 'water_requests';
	}
	
	public function scopes()
    {
        return array(
            'saved'=>array(
            	'condition'=>'status=\''.WaterRequests::SW_NODE(WaterRequests::SAVED_STATUS).'\'',
                'order'=>'id ASC',
            ),
            'no_tmp'=>array(
            	'condition'=>'status!=\''.WaterRequests::SW_NODE(WaterRequests::TEMP_STATUS).'\'',
                'order'=>'id ASC',
            ),
            
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, project', 'required'),
			array('phase, parent_phase, cost', 'numerical', 'integerOnly'=>true),
			array('username, file_link', 'length', 'max'=>255),
			array('project', 'length', 'max'=>500),
			array('description, note', 'length', 'max'=>1000),
			array('status',  'SWValidator','enableSwValidation'=>true),
			array('cost','required','on'=>'sw:submitted_approved'),
			array('expiration_date','required','on'=>'phase_two'),
			array('expiration_date','safe'),
			//array('timestamp', 'safe'),
			array('geometries', 'almenouno', 'on'=>'insert'),
			array('shpfile', 'file', 'allowEmpty' => false,'types'=>'shp', 'on'=>'upload'),
			//array('shxfile', 'file', 'allowEmpty' => false,'types'=>'shx', 'on'=>'upload'),
			//array('dbffile', 'file', 'allowEmpty' => false,'types'=>'dbf', 'on'=>'upload'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, timestamp, username, project, description, status, phase, parent_phase, note, expiration_date', 'safe', 'on'=>'search'),
		);
	}

	public function almenouno($attribute,$params)
    {       
        //Yii::log('attributo '.$this->$attribute, CLogger::LEVEL_INFO, '');
    	if (count($this->$attribute)<=0)
            $this->addError($attribute, "La lottizzazione deve avere almeno un lotto inserito");
    }
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'geometries'=>array(self::HAS_MANY, 'WaterRequestGeometries', 'wr_id','order'=>'id ASC', ),
			'user'=>array(self::BELONGS_TO,'Users', 'username'),
			'parent_wr'=>array(self::BELONGS_TO,'WaterRequests', 'parent_phase')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'timestamp' => 'Date & Time',
			'timestampHR' => 'Date & Time',
			'username' => 'Username',
			'project' => 'Project',
			'description' => 'Description',
			'status' => 'Status',
			'phase' => 'Phase',
			'parent_phase' => 'Parent Phase',
			'total_water_demand' => 'Total Water Demand',
			'rounded_water_demand'=> 'Total Water Demand',
			'effective_water_demand' => 'Effective Water Demand',
			'geometries' => 'Geometries',
			'cost'=> 'Cost',
			'file_link'=> 'Comment file',
			'note' => 'Note',
			'shpfile' => 'SHP File',
			'shxfile' => 'SHX File',
			'dbffile' => 'DBF File',
			'fileproj' => 'Projection',
			'parent_water_request'=>'Parent Water Request',
			'parent_water_demand'=>'Parent Water Demand',
			'parent_water_demand_usage'=>'Parent Water Demand Usage',
			'expiration_date' => 'Expiration Date',
				
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
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('project',$this->project,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('phase',$this->phase);
		$criteria->compare('parent_phase',$this->parent_phase);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public static function allStatuses(){
		return array(
					WaterRequests::TEMP_STATUS,
					WaterRequests::SAVED_STATUS,
					WaterRequests::SUBMITTED_STATUS,
					WaterRequests::CANCELLED_STATUS,
					WaterRequests::APPROVED_STATUS,
					WaterRequests::REJECTED_STATUS,
					WaterRequests::IN_FUTURE_STATUS,
					WaterRequests::CONFIRMED_STATUS,
					WaterRequests::REFUSED_STATUS,
					WaterRequests::IN_PROGRESS_STATUS,
					WaterRequests::TIMEOUT_STATUS,
					WaterRequests::COMPLETED_STATUS,
				);
	}
	
	//TODO: da leggere dal database
	public function getTypeOptions()
	{
    	return array('B' => 'Completamento Residenziale', 'C' => 'Espansione Residenziale',
					'R' => 'Piani di Recupero Residenziale', 'E' => 'Turistico Ricettive',
					'D' => 'Completamento e/o Espansione Produttiva');
	}
	
	/**
	 * Makes the field 'status' human readable
	 * TEMP_STATUS => Unsaved;
	 * SAVED_STATUS => Saved;
	 * SUBMITTED_STATUS => Submitted;
	 * CANCELLED_STATUS => Canceled;
	 * APPROVED_STATUS => Approved;
	 * REJECTED_STATUS => Rejected;
	 */
	public function getStatusHR() {
		$s = explode("/", $this->swGetStatus()->toString());
		if (isset($s[1]))
			return ucfirst($s[1]);
	}
	
	public function getStatusIcon() {
		$text = $this->statusHR;
		if ($text)
			return "<span class='status_label' id='".strtolower($text)."'>".ucfirst(Yii::t('waterrequest',strtolower($text)))."</span>";
	}
	
	public function getStatusIconHistory() {
		$text = $this->statusHR;
		if ($text)
			return "<span class='status_label' id='".strtolower($text)."' onclick='infoHistory($(this),$this->id);'>".ucfirst(Yii::t('waterrequest',strtolower($text)))."</span>";
	}
	
	
	public function getStatusID($status) {
		if ($status)
			return $this->swGetWorkflowId().'/'.$status;
		return $this->swGetWorkflowId().'/'.$this->status;
	}
	
	// static?
	/*
	public function Editable($model) {
		
		switch ($model->status){
			case WaterRequests::TEMP_STATUS:
			case WaterRequests::SAVED_STATUS:
				return true;
			case WaterRequests::SUBMITTED_STATUS:
			case WaterRequests::CANCELLED_STATUS:
			case WaterRequests::APPROVED_STATUS:
			case WaterRequests::REJECTED_STATUS:
				return false;	
			default:
				Yii::log('status errato ='.$model->status. ' id='.$model->id, CLogger::LEVEL_INFO, 'isEditable');
				return false;					
		}
		return true;
	}*/
	
	public function isEditable() {
		switch ($this->status) {
			case WaterRequests::SW_NODE(WaterRequests::TEMP_STATUS):
			case WaterRequests::SW_NODE(WaterRequests::SAVED_STATUS):
			case WaterRequests::SW_NODE(WaterRequests::REJECTED_STATUS):
				return true;
			default:
				return false;
		}
	}


	/**
	 * Converts timestamp into a string human readable
	 * The datetime format is specifed by dateTimeFormat param in config/main.php
	 * 
	 */
	public function getTimestampHR() {
		$date = strtotime($this->timestamp);
		try{
			/*$ret = date(Yii::app()->params['dateTimeFormat'],$date);*/
			$ret = Yii::app()->dateFormatter->formatDateTime($date, 'long', false).', '.Yii::app()->dateFormatter->formatDateTime($date, '', 'short', false);
		}
		catch(Exception $e){
			$ret = date('j F Y, H:i',$date);//default datetime format
		}
		return $ret;
	}

	/**
	 * Converts timestamp into a string human readable (only date part)
	 * The date format is specifed by dateFormat param in config/main.php
	 */
	public function getDateHR() {
		$date = strtotime($this->timestamp);
		try{
			$ret = date(Yii::app()->params['dateFormat'],$date);
		}
		catch(Exception $e){
			$ret = date('Y-m-d',$date);//default date format
		}
		return $ret;
	}
	
	public function getPhaseHR() {
		if ($this->phase==1)
			return Yii::t('waterrequest', 'Preliminary Phase');
		if ($this->phase==2)
			return Yii::t('waterrequest', 'Implementation Phase');
		if ($this->phase==3)
			return Yii::t('waterrequest', 'Executive Phase');
		return '';
	}
	
	/**
	 * 
	 */
	public function getRounded_water_demand()
	{
		return Math::wd_round($this->total_water_demand);
	}
	
	/**
	 * Updates status 
	 */
	public function updateStatus($new_status)
	{
		if ($this->swIsNextStatus($new_status)) {
			$this->status = $new_status;
			return true;
		}
		return false;
	}
	
	public static function SW_NODE($status) {
		$node = new SWNode($status,WaterRequests::WORKFLOW);
		if( $node)
			return $node->toString();
		return '';
	}
	
	/** 
	 * This method is invoked after saving a record.
	 * It creates a new Notification item
	 */
	public function afterSave()
	{
		//perform this operation only if the status is submitted	
    	/*
    	if ($this->status==WaterRequests::SW_NODE(WaterRequests::SUBMITTED_STATUS)){
	    	//Notification
			Notifications::generate('WaterRequests',$this->id,'waterrequests','create');
		}*/
			
  		return parent::afterSave();
	}
	
	public function getBBox(){
		
		if(!$this->id)  // WaterRequestGeometries not inizialized
			return null;
			
		$connection=Yii::app()->db;
		$sql = 'SELECT ST_Xmin(ST_Extent(geom)) as Xmin, ST_Ymin(ST_Extent(geom)) as Ymin, ST_Xmax(ST_Extent(geom)) as Xmax, ST_Ymax(ST_Extent(geom)) as Ymax FROM '.WaterRequestGeometries::model()->tableName().' WHERE wr_id='.$this->id.' GROUP BY wr_id;';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows, true) , CLogger::LEVEL_INFO, 'getBBox');  // DEBUG
		if(count($rows))
			return $rows[0];
		else
			return null;
		
	}

	public function imageSRC() {
		$service = 'WMS';
		$request = 'GetMap';
		$style='';
		$width=500;
		$height=300;
		$format='image/jpeg';
		$param='wr_id';
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
				'&layers='.Yii::app()->params['geoserver']['workspace'].':'.'rst100k'.
						 	','.
						 	Yii::app()->params['geoserver']['workspace'].':'.Yii::app()->params['geoserver']['pdf_geoms'].
				'&style='.$style.
				'&bbox='.$boundy_box.
				'&width='.$width.
				'&height='.$height.
				'&srs='.Yii::app()->params['geoserver']['default_srs'].
				'&format='.$format.
				'&viewparams='.$param.':'.$this->id;
				//Yii::log($ret, CLogger::LEVEL_INFO, "imageSRC_wr");  // DEBUG
		return $ret;
		
	}
	
	public function imageTag(){
		return '<img src="'.$this->imageSRC().'" />';
	}
	
	public function getCityStates(){
		
		if(!$this->id)  // WaterRequestGeometries not inizialized
			return null;
		// Query runtime: 11ms - ~102ms . 	
		$connection=Yii::app()->db;
		$sql = '	SELECT	DISTINCT confini_comunali.nome as city_state
					FROM	confini_comunali,
						(	SELECT id, transform(geom, '.Yii::app()->params['geoserver']['citystate_layer_srid'].') as geom
							FROM '.WaterRequestGeometries::model()->tableName().'
							WHERE wr_id='.$this->id.'
						) lotto
					WHERE st_intersects(
							confini_comunali.the_geom,
							lotto.geom
						)
				;';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows = array();
		while ($row = $dataReader->readcolumn(0)) {
		    $rows[] = $row;
		}
		//$rows=$dataReader->readAll();
		//Yii::log(print_r($rows, true) , CLogger::LEVEL_INFO, 'getCityStates');  // DEBUG
		return $rows;
		
	}
	
	/**
	 * Returns a String listing all the zones of the WaterRequest, or null if no zones are found.
	 */
	public function getZonesString(){
		
		if(!$this->id)  // WaterRequestGeometries not inizialized
			return null;
		// Query runtime: 11ms - ~102ms . 	
		$connection=Yii::app()->db;
		$sql = 'SELECT array_to_string(
				ARRAY(
			        SELECT zone 
			        FROM water_request_geometries AS g, water_request_geometry_zones AS z
					WHERE  g.id = z.wr_geometry_id AND g.wr_id = '.$this->id.'
					)
				,\',\')as label ;
				';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		//Yii::log(print_r($rows, true) , CLogger::LEVEL_INFO, 'getZonesString');  // DEBUG
		if(count($rows))
			return $rows[0]['label'];
		else
			return null;
				
	}
	
	public function getParentWDUsage($include_current=true)
	{
		if (($this->phase==2)||($this->phase==3)) {
			$parents = WaterRequests::model()->findAllByAttributes(
				array(
					'username'=>$this->user->username,
					'phase'=>$this->phase,
					'parent_phase'=>$this->parent_wr->id,
				)
			);
			
			$usage = 0;
			foreach($parents as $parent) {
				if ((!$include_current) && ($parent->id == $this->id))
					continue;
				$usage+=$parent->total_water_demand;	
			}
			return $usage;
			
		}
		return 0;
	}
	
	public function updateOperativeMargin() {
		if ((($this->status==WaterRequests::CONFIRMED_STATUS) && ($this->phase==2)) || (($this->status==WaterRequests::COMPLETED_STATUS) && ($this->phase==3))){
			//loop into geoms
			foreach ($this->geometries as $geom) {
				$service_area = Geometry::Get_Service_Area_Detailed($geom->id);
				$city_state = Geometry::Get_City_State($geom->id);
				
				if($service_area == null){
					$operative_margin = OperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($city_state)));
					$dummy_operative_margin = DummyOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($city_state)));
					if ((count($operative_margin)==0) || (count($dummy_operative_margin)==0)) {
						Yii::log('Manca il margine operativo del comune. area='.$city_state , CLogger::LEVEL_INFO, 'updateOperativeMargin');  // DEBUG
						return;
					}
				}
				else{
					$operative_margin = SAOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($service_area['area'])));
					$dummy_operative_margin = DummySAOperativeMargin::model()->findAll('lower(area)=:area',array(':area'=>strtolower($service_area['area'])));
					if ((count($operative_margin)==0) || (count($dummy_operative_margin)==0)) {
						Yii::log('Manca il margine operativo dell\'area di servizio. area='.$city_state , CLogger::LEVEL_INFO, 'updateOperativeMargin');  // DEBUG
						return;
					}
				}
				
				if ($this->phase==2) {
					foreach($dummy_operative_margin as $dop) {
						$dop->margin = $dop->margin - $geom->geom_water_demand;
						$dop->save();
					}	
				}
				else {
					foreach($operative_margin as $op) {
						$op->margin = $op->margin - $geom->geom_water_demand;
						$op->save();
						if ($this->parent_wr) {
							$parent = $this->parent_wr;
							foreach ($parent->geometries as $g) {
								$sa = Geometry::Get_Service_Area_Detailed($g->id);
								$cs = Geometry::Get_City_State($g->id);
								if ((strcmp($service_area,$sa)===0) && (strcmp($city_state,$cs)===0)){
									foreach($dummy_operative_margin as $dop) {
										$dop->margin = $dop->margin + $geom->geom_water_demand;
										$dop->save();
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	public function behaviors()
	{
	    return array(
	        'swBehavior'=>array(
	            'class' => 'application.extensions.simpleWorkflow.SWActiveRecordBehavior',
	        ),
	        
	    );
	}
	
	protected function beforeDelete()
	{
		$geom = WaterRequestGeometries::model()->findAll('wr_id = :wr_id', array(':wr_id' => $this->id));
		if ($geom) {
			foreach($geom as $g) {
				$geom_zone = WaterRequestGeometryZones::model()->findAll('wr_geometry_id = :wr_geometry_id', array(':wr_geometry_id' => $g->id));
				if ($geom_zone) {
					foreach($geom_zone as $z) {
						WaterRequestGeometryZoneProperties::model()->deleteAll('geometry_zone = :geometry_zone', array(':geometry_zone' => $z->id));
					}
					WaterRequestGeometryZones::model()->deleteAll('wr_geometry_id = :wr_geometry_id', array(':wr_geometry_id' => $g->id));
				}
			}
			WaterRequestGeometries::model()->deleteAll('wr_id = :wr_id', array(':wr_id' => $this->id));
		}
		return parent::beforeDelete();
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