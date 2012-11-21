<?php

/**
 * Model to support the EPANET form creation
 */
class EpanetForm extends CFormModel
{
	public $junction_id;
	public $description;
	public $tag;
	public $demand_pattern;
	public $demand_categories;
	public $emitter_coeff;
	public $initial_quality;
	public $source_quality;
	
	public $filename;
	public $srid;
	public $other_srid;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'junction_id'=>'Junction ID',
			'description'=>'Description',
			'tag'=>'Tag',
			'demand_pattern'=>'Demand Pattern',
			'demand_categories'=>'Demand Categories',
			'emitter_coeff'=>'Emitter Coeff.',
			'initial_quality'=>'Initial Quality',
			'source_quality'=>'Source Quality',
			'filename'=>'Filename',
			'srid'=>'Input SRID',
			'other_srid'=>'Inserisci SRID'
		);
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
	
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function aaa()
	{
		
	}
}
