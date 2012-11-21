<?php

/**
 * This is the model class for table "plugins".
 *
 * The followings are the available columns in table 'plugins':
 * @property string $name
 * @property string $username
 * @property string $timestamp
 * @property string $version
 * @property integer $priority
 * @property string $users
 * @property string $status
 * @property boolean $enable
 * @property description $description
 * @property changelog $changelog
 *
 * The followings are the available model relations:
 * @property Users $username0
 */
class Plugins extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Plugins the static model class
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
		return 'plugins';
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
			array('priority', 'numerical', 'integerOnly'=>true),
			array('name, username, users', 'length', 'max'=>255),
			array('version', 'length', 'max'=>50),
			array('status', 'length', 'max'=>3000),
			array('description', 'length', 'max'=>500),
			array('changelog', 'length', 'max'=>500),
			array('timestamp, enable', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, username, timestamp, version, priority, users, status, description, changelog, enable', 'safe', 'on'=>'search'),
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
			'username0' => array(self::BELONGS_TO, 'Users', 'username'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'username' => 'Username',
			'timestamp' => 'Timestamp',
			'version' => 'Version',
			'priority' => 'Priority',
			'users' => 'Users',
			'status' => 'Status',
			'enable' => 'Enable',
			'description' => 'Description',
			'changelog' => 'Changelog'
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('users',$this->users,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('enable',$this->enable);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function rrmdir($dir) {
		foreach(glob($dir . '/*') as $file) {
        	if(is_dir($file))
           		Plugins::rrmdir($file);
        	else
            	unlink($file);
    	}
    	rmdir($dir);
	}
	
	public static function processArchive($dir,$file) {
			
		$_error = 'ok';
		
		$pluginName = substr($file, 0, -4);
		
		$destDir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$pluginName;
		
		$model = Plugins::model()->findByPk($pluginName);
		
		//remove plugin entry
		if ($model) {
			$model->delete();
		}
		
		//remove dir
		if(file_exists($destDir) )
			Plugins::rrmdir($destDir);
		
		//remove plugin table
		$connection=Yii::app()->db;
		try {
			$command=$connection->createCommand('DROP TABLE '.$pluginName.';')->execute();	
		}
		catch (Exception $e){
			$_error = $e;
		}

		$zip = zip_open($dir.$file);
		$dir = Plugins::extractZip($pluginName,$dir,$file,$destDir);
		
		if ($dir) {
			
			$version = $description = $changelog = $sql = '';
			
			//Open images directory
			$d = opendir($dir);

			//List files in images directory
			while (($file = readdir($d)) !== false) {
				if (strpos($file,'.info') !== false) {
					
					$_in_file = fopen($dir.$file,'r');
					while ($line=fgets($_in_file)) {
						if ($line===FALSE) {
							//ERROR
							echo 'error';
							return;
						}			
						if (preg_match('/^\[[a-zA-Z0-9]{1,}\]/',$line,$matches)) {
							$section = str_replace(array('[',']'), array('',''),$matches[0]);
							continue;
						}
						if ($section == 'version')
							$version.=$line;
						if ($section == 'description')
							$description.=$line;
						if ($section == 'changelog')
							$changelog.=$line;
					}
					
					//$version = file_get_contents($dir.$file);
					//$description = '';
					continue;
				}
				
				if (strpos($file,'.sql') !== false) {
					$sql = file_get_contents($dir.$file);
					continue;
				}
			}
			closedir($d);
			
			if ($sql) {
				$connection=Yii::app()->db;
				try {
					$command=$connection->createCommand($sql)->execute();
				}
				catch (Exception $e){
					$_error = $e;
				}
			}
			
			$plugin = new Plugins;
			$plugin->name = 'Foo';
			$plugin->username = Yii::app()->user->id;
			$plugin->timestamp = date(Yii::app()->params['dateTimeFormatDB']);
			$plugin->version = $version;
			$plugin->description = $description;
			$plugin->changelog = $changelog;
			$plugin->priority = 0;
			$plugin->users = '*';
			$plugin->status = substr($_error,0,4000); //truncate to max length
			$plugin->enable = true;
			
			if ($plugin->save())
				return Yii::app()->createUrl($pluginName.'/'.$pluginName.'/index', array());
		}
		return;
		
	}

	public static function extractZip($pluginName, $zipDir, $zipFile, $dest) {   
    	
    	$dirFromZip = $zipDir;
    	$zip = zip_open($zipDir.$zipFile);
		
		$destDir = null;
	    if ($zip) {
	    	$destDir = $dest.DIRECTORY_SEPARATOR;
	    	if(!file_exists($destDir) ) {
            	@mkdir($destDir, 0777);
			}
        	while ($zip_entry = zip_read($zip)) {
        		
            	$completePath = $destDir . dirname(zip_entry_name($zip_entry));
            	$completeName = $destDir . zip_entry_name($zip_entry);
							
            	// Walk through path to create non existing directories
            	// This won't apply to empty directories ! They are created further below
            	if(!file_exists($completePath) && preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) ) {
                	$tmp = '';
                	foreach(explode(DIRECTORY_SEPARATOR,$completePath) AS $k) {
                    	$tmp .= $k.DIRECTORY_SEPARATOR;
                    	if(!file_exists($tmp) ) {
                        	@mkdir($tmp, 0777);
                    	}
                	}
            	}

            	if (zip_entry_open($zip, $zip_entry, "r")) {
                
            		if ($fd = @fopen($completeName, 'w+')) {
						fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
						fclose($fd);
					}
					else{
						mkdir($completeName, 0777);
					}
					zip_entry_close($zip_entry);
				}
        	}
        	zip_close($zip);
   		}
    	return $destDir;
	}
}