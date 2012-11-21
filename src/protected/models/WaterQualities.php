<?php

/**
 * This is the model class for table "water_qualities".
 *
 * The followings are the available columns in table 'water_qualities':
 * @property int $id the progressive quality 
 * @property string $quality the quality of water service ( excellent,good,decent,low,awful )
 * @property string $color the RGB color associated with quality
 * @property int $priority the display order of the types opinion
 * @property boolean $active
 * @property string $image the path of the icon displayed on the map
 */
class WaterQualities extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WaterQualities the static model class
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
		return 'water_qualities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(

			//primary key
			array('id,color','unique'),
			
			//max_length
			array('quality', 'length', 'max'=>50),
			array('color', 'length', 'max'=>7),
			array('image', 'length', 'max'=>250),
			
			//required
			array('quality,color,priority,active', 'required'),
			
			//The following rule is used by search().
			array('id,quality,color,priority,active,image', 'safe', 'on'=>'search'),
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
			'quality' => Yii::t('qualities','Quality'),
			'color' => Yii::t('qualities','Color'),
			'priority' => Yii::t('qualities','Priority'),
			'active' => Yii::t('qualities','Active'),
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
		$criteria->compare('quality',$this->quality,true);
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