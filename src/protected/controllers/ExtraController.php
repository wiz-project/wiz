<?php

class ExtraController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$plugins = Plugins::model()->findAllByAttributes(array('status'=>'ok'));
		$this->render('index',array(
			'plugins'=>$plugins,
		));
	}
	

}