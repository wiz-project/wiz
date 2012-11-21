<?php

/**
 * This is the model class for table "water_faults".
 *
 * The followings are the available columns in table 'water_faults':
 * @property int $id the progressive fault 
 * @property string $fault the fault of water service
 * @property string $color the RGB color associated with fault
 * @property int $priority the display order of the types opinion
 * @property boolean $active
 * @property string $image the path of the icon displayed on the map
 */
class WaterFaults extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterFaults the static model class
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
		return 'water_faults';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('id','unique'),
			
			//required
			array('fault,color,priority,active', 'required'),
			
			//max_length
			array('fault', 'length', 'max'=>50),
			array('color', 'length', 'max'=>7),
			array('image', 'length', 'max'=>250),
			
			//The following rule is used by search().
			array('id,fault,color,priority,active,image', 'safe', 'on'=>'search'),
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
			
		);
	}

	/**
	 * 
	 */
	public function scopes()
    {
		return array(
			'view'=>array(
                'condition'=>'active=true ORDER BY priority',
            ),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fault' => Yii::t('faults','Fault'),
			'color' => Yii::t('faults','Color'),
			'priority' => Yii::t('faults','Priority'),
			'active' => Yii::t('faults','Active'),
			'image' => Yii::t('faults','Icon'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('fault',$this->fault,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('priority',$this->priority,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('active',$this->image,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
	            'defaultOrder'=>'priority ASC',
	        ),
		));
	}
}