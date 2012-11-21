<?php

class WaterInfoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/wrapper.php'.
	 */
	public $layout='//layouts/wrapper';

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
			array('allow',  // allow all users to perform 'index' and 'create' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Displays the possible operations (view/create estimation).
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	

}
