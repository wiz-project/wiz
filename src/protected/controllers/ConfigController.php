<?php

class ConfigController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	
	/**
	 * Views all system params.
	 */
	public function actionIndex() 
	{
		if (!Yii::app()->user->checkAccess('viewSystemParams'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$params = include($file = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php');
	
		$index = 1;
		$rawData = array();
		while(list($key,$value)=each($params)) {
			if(is_array($value)) {
				array_push($rawData,array('ID'=>$index,'Parameter'=>$key,'Value'=>''));
				while(list($subkey,$subvalue)=each($value)) 
					array_push($rawData,array('ID'=>'','Parameter'=>$subkey,'Value'=>$subvalue));
			} else 
				array_push($rawData,array('ID'=>$index,'Parameter'=>$key,'Value'=>$value));
			$index++;
		}
	 
		$dataProvider=new CArrayDataProvider($rawData, array(
			'keyField'=>'ID',
			'pagination'=>array(
				'pageSize'=>10,
			),	
		)); 
	 
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Creates a new system param.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
    public function actionCreate()
    {
		if (!Yii::app()->user->checkAccess('createSystemParam'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$model = new Config;
		
		$params = include($file = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php');
		$params_list = array();
		while(list($key,$value)=each($params)) {
			if(is_array($value))
				array_push($params_list,$key);
		}
		
		if(isset($_POST['Config'])) {
			$model->attributes=$_POST['Config'];
			if(is_numeric($model->param_value))
				$model->param_value = (int)$model->param_value;
			if($model->validate()) {
				if($_POST['param_parent'] == "")
					$params[$model->param_name] = $model->param_value;
				else
					$params[$params_list[$_POST['param_parent']]][$model->param_name] = $model->param_value;
				
				try {
					copy(Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php',Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params_old.php');
					$file = fopen($file = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php', 'w');
					fwrite($file, "<?php\n\r return ");
					fwrite($file, var_export($params, TRUE));
					fwrite($file, ";\n\r ?>");
					fclose($file);
					
					Yii::app()->user->setFlash('success','The creation of the new system param has been performed successfully.');
					$this->redirect(array('index'));
				} catch(Exception $e) {
					Yii::app()->user->setFlash('error','Saving new system param failed. Please, try again.');
				}
			}
		}
		
        $this->render('create',array('model'=>$model,'params_list'=>$params_list));
    }
	
	/**
	 * Updates a system param.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionUpdate($id)
    {	
		if (!Yii::app()->user->checkAccess('updateSystemParam'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$params = include($file = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php');
		
		$model = new Config;
		$params_list = array();
		if(array_key_exists($id,$params)) 
			$model->attributes=array('param_name'=>$id,'param_value'=>$params[$id]);
		else {
			while(list($key,$value)=each($params)) {
				if(is_array($value)) {
					if(array_key_exists($id,$value)) 
						$model->attributes=array('param_name'=>$id,'param_value'=>$value[$id]);
					array_push($params_list,$key);
				}
			}
		}
		
		if(isset($_POST['Config'])) {
			$model->attributes=$_POST['Config'];
			if(is_numeric($model->param_value))
				$model->param_value = (int)$model->param_value;
			if($model->validate()) {
				
				if(empty($_POST['param_parent']))
					$params[$model->param_name] = $model->param_value;
				else
					$params[$_POST['param_parent']][$model->param_name] = $model->param_value;
					
				try {
					copy(Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php',Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params_old.php');
					$file = fopen($file = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'params.php', 'w');
					fwrite($file, "<?php\n\r return ");
					fwrite($file, var_export($params, TRUE));
					fwrite($file, ";\n\r ?>");
					fclose($file);
					
					Yii::app()->user->setFlash('success','The update of the system param has been performed successfully.');
					$this->redirect(array('index'));
				} catch(Exception $e) {
					Yii::app()->user->setFlash('error','Editing of the system param failed. Please, try again.');
				}
			}
		}
		
        $this->render('update',array('model'=>$model,'params_list'=>$params_list));
    }
}

?>