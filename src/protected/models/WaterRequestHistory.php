<?php

/**
 * This is the model class for table "water_request_history".
 *
 * The followings are the available columns in table 'water_request_history':
 * @property integer $wr_id
 * @property string $timestamp
 * @property string $comment
 * @property string $status
 */
class WaterRequestHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return WaterRequestHistory the static model class
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
		return 'water_request_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wr_id, timestamp', 'required'),
			array('wr_id', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>1000),
			array('status', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('wr_id, timestamp, comment, status', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'wr_id' => 'Wr',
			'timestamp' => 'Timestamp',
			'comment' => 'Comment',
			'status' => 'Status',
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

		$criteria->compare('wr_id',$this->wr_id);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Converts timestamp into a string human readable (only date part)
	 * The date format is specifed by dateFormat param in config/main.php
	 */
	public function getDateHR() {
		$date = strtotime($this->timestamp);
		try{
			$ret = date(Yii::app()->params['dateFormat'],$date);
		}
		catch(Exception $e){
			$ret = date('Y-m-d',$date);//default date format
		}
		return $ret;
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
	
}