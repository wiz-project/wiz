<?php

class PluginsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index'
				'actions'=>array('index','upload'),
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		/*
		$dataProvider=new CActiveDataProvider('Plugins');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		$model=new Plugins('search');
		$model->unsetAttributes();  // clear any default values
	    if(isset($_GET['Plugins']))
	        $model->attributes =$_GET['Plugins'];
		
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionUpload() {
		$this->layout='//layouts/column1';
		$this->render('upload',array());
		
	}
	
		/**
	 * Handle the upload of an inp file
	 * @param string $qqfile unused parameter
	 */
	public function actionPluginFileUpload($qqfile=null)
	{
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("zip", "tar", "rar");
		// max file size in bytes
		$sizeLimit = Yii::app()->params['plugins']['upload_max_size'] * 1024 * 1024;

		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$dir = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.Yii::app()->params['plugins']['upload_dir'].DIRECTORY_SEPARATOR;
		$result = $uploader->handleUpload($dir,true);
		// to pass data through iframe you will need to encode all html tags
		if (!$result['success'])
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		
		$r = Plugins::processArchive($dir,$qqfile);
		if ($r)
			echo htmlspecialchars(json_encode(array('success'=>true,'url'=>$r)), ENT_NOQUOTES);
	}


}