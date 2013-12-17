<?php

/**
 * This is the model class for table "object_coordinates".
 *
 * The followings are the available columns in table 'object_coordinates':
 * @property integer $id
 * @property double $lng
 * @property double $lat
 * @property integer $object_id
 * @property integer $index
 */
class ObjectCoordinates extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'object_coordinates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('longitude, latitude, object_id, index', 'required'),
			array('object_id, index', 'numerical', 'integerOnly'=>true),
			array('longitude, latitude', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, longitude, latitude, object_id, index', 'safe', 'on'=>'search'),
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
            'object'=>array(self::BELONGS_TO, 'Object', 'object_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lng' => 'Longitude',
			'lat' => 'Latitude',
			'object_id' => 'Object',
			'index' => 'Index',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('lng',$this->longitude);
		$criteria->compare('lat',$this->latitude);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('index',$this->index);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ObjectCoordinates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
