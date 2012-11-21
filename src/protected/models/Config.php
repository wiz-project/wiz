<?php

/**
 * Config class.
 * Config is the data structure for manage of system params
 */
class Config extends CFormModel {
		
	/**
	 * @var string the name of system param
	 */
	public $param_name;
	/**
	 * @var string the value of system param
	 */
	public $param_value;
		
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//required
			array('param_name, param_value', 'required'),	
		);
	}
		
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	*/
	public function attributeLabels()
	{
		return array(
			'param_name'=>Yii::t('config','Name'),
			'param_value'=>Yii::t('config','Value'),
		);
	}
}
		
?>