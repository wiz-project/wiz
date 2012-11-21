<?php

class FileUploadController extends Controller
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
			array('allow',  // allow all users to perform 'upload','save' actions
				'actions'=>array('upload','save'),
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Save xls contents on the table identified by the module.
	 * If rescue is successful, the browser will be redirected to the 'report' page. 
	 * @param string $filename the name of file to save
	 */
	public function actionSave($filename)
	{
	
		if (!Yii::app()->user->checkAccess('loadExcel'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		if(empty($filename))
			$filename = $_POST['filename'];
			
		Yii::import('application.vendors.PHPExcel',true);
		$objReader = new PHPExcel_Reader_Excel5;
		$objPHPExcel = $objReader->load(Yii::app()->basePath . '/../excel_uploads/' . $filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow(); 
		$highestColumn = $objWorksheet->getHighestColumn(); 
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
	
		$errors = array();
		if(isset($_POST['table']) && !empty($_POST['table'])) {
			$green = $yellow = $red = 0;
			$success_msg = "[ ".Yii::t('excel','Inserted rows')." ]<br><br>";
			$error_msg = "[ ".Yii::t('excel','Not inserted rows')." ]<br><br>";
			$warning_msg = "[ ".Yii::t('excel','Updated rows')." ]<br><br>";
			for ($row = 2; $row <= $highestRow; ++$row) {
				$model_save = new $_POST['table'];
				foreach($model_save->attributeNames() as $attribute) {
					if($_POST['attr_'.$attribute] != "") { 
						$model_save->setAttribute($attribute,$objWorksheet->getCellByColumnAndRow($_POST['attr_'.$attribute], $row)->getValue());
					}
				}
		
				if($model_save->save()) {
					$success_msg .= Yii::t('excel','ROW ').($row-1).": ".Yii::t('excel','insertion completed').".<br>";
					$green++;
				}
				else {
					$message = array();
					foreach($model_save->attributeNames() as $attribute) {
						if(isset($model_save->errors[$attribute])) {
							if(stripos($model_save->errors[$attribute][0],"already") > 0) {
								$model_update = $this->loadModel($objWorksheet->getCellByColumnAndRow($_POST['attr_'.$attribute], $row)->getValue(),$_POST['table']);
								foreach($model_update->attributeNames() as $attribute_update) {
									if($_POST['attr_'.$attribute_update] != "") { 
										$model_update->setAttribute($attribute_update,$objWorksheet->getCellByColumnAndRow($_POST['attr_'.$attribute_update], $row)->getValue());
									}
								}
								if($model_update->save()) {
									$yellow++;
									$warning_msg .= Yii::t('excel','ROW ').($row-1).": ".$model_save->errors[$attribute][0]."<br>";
								} else {
									$red++;
									$error_msg .= Yii::t('excel','ROW ').($row-1).": ".$model_update->errors[$attribute_update][0]."<br>";
								}
							}
							else {
								$red++;
								$error_msg .= Yii::t('excel','ROW ').($row-1).": ".$model_save->errors[$attribute][0]."<br>";
							}
						}
					}
				} 
			}
			
			$success_msg .= "<br>".Yii::t('excel','TOTAL ROWS').": ".$green;
			$warning_msg .= "<br>".Yii::t('excel','TOTAL ROWS').": ".$yellow;
			$error_msg .= "<br>".Yii::t('excel','TOTAL ROWS').": ".$red;
			
			Yii::app()->user->setFlash('success',$success_msg);
			Yii::app()->user->setFlash('warning',$warning_msg);
			Yii::app()->user->setFlash('error',$error_msg);
			$this->render('report',array('filename'=>$filename));
			Yii::app()->end();
		}
		
		$setParams = false;
		$arrayData = array();
		$columnsArray = array();
		$xlsAttributes = array();
		for ($row = 2; $row <= $highestRow; ++$row) {
			$rawData = array();
			$rawData['id'] = ($row-1);
			for ($col = 0; $col <= $highestColumnIndex-1; ++$col) { 
				if(!$setParams) {
					$columnArray = array("name"=>$objWorksheet->getCellByColumnAndRow($col, 1)->getValue(),"type"=>"raw","value"=>'$data["'.$objWorksheet->getCellByColumnAndRow($col, 1)->getValue().'"]');
					array_push($columnsArray,$columnArray);
					array_push($xlsAttributes, $objWorksheet->getCellByColumnAndRow($col, 1)->getValue());
				}
				$rawData[$objWorksheet->getCellByColumnAndRow($col, 1)->getValue()] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
			array_push($arrayData,$rawData);
			$setParams = true;
		}
	
		$arrayDataProvider=new CArrayDataProvider($arrayData, array(
	        'id'=>'id',
	        'pagination'=>array(
				'pageSize'=>10,
	        ),
	    ));
	 
		$this->render('save',array(
			'filename'=>$filename,
			'arrayDataProvider'=>$arrayDataProvider,
			'xlsAttributes'=>$xlsAttributes,
			'columnsArray'=>$columnsArray,
		));
	}
	
	/** 
	 * Uploads the xls file on system.
	 * The browser displays the result of this operation.
	 */
	public function actionUpload()
    {
		if (!Yii::app()->user->checkAccess('loadExcel'))
			throw new CHttpException(403,Yii::t('http_status', '403'));
	
		$upload = false;
        $model=new FileUpload;
        if(isset($_POST['FileUpload']))
        {
            $model->attributes=$_POST['FileUpload'];
			if($model->validate()) {
				$upload = true;
				$model->uploaded_file = CUploadedFile::getInstance($model,'uploaded_file');
				$model->uploaded_file->saveAs(Yii::app()->basePath . '/../excel_uploads/' . $model->uploaded_file);
				
				$this->render('upload',array(
					'model'=>$model,
					'upload'=>$upload,
					'filename'=>$model->uploaded_file
				));
				Yii::app()->end();
			}
        }
		
		$this->render('upload', array('model'=>$model,'upload'=>$upload));
    }
	
	/**
	 * Ajax Call - Returns the list of attributes of the xls file.
	 */
	public function actionUpdateFields() {
		$xlsAttributes = json_decode($_POST['xlsAttributes']);
		if(isset($_POST)) {
			$model_fields = new $_POST['table'];
			echo "<p class=\"note\">".Yii::t('excel','Attach the columns to table fields')."</p>";
			foreach($model_fields->attributeNames() as $attribute)
			{
				echo "<div class=\"jFormComponent\">";
				echo CHtml::label($attribute, 'label_'.$attribute);
				$selected = null;
				for ($col = 0; $col <= count($xlsAttributes)-1; ++$col) {
					if($xlsAttributes[$col] == $attribute)
						$selected = $col;
				}
				echo CHtml::dropDownList('attr_'.$attribute, $selected, $xlsAttributes, array('empty'=>Yii::t('excel','--select field--')));
				echo "</div>";
			}
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the identifier of the model to be loaded
	 * @param string $load_model the name of model to be loaded
	 */
	public function loadModel($id,$load_model)
	{
		$model= CActiveRecord::model($load_model)->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('http_status',404));
		return $model;
	}
}

?>