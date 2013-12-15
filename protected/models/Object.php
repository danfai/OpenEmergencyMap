<?php

/**
 * This is the model class for table "object".
 *
 * The followings are the available columns in table 'object':
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $description
 */
class Object extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'object';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, name, description', 'required'),
			array('type', 'length', 'max'=>30),
			array('name', 'length', 'max'=>64),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, name, description', 'safe', 'on'=>'search'),
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
            'attributes'=>array(self::HAS_MANY, 'ObjectAttributes', 'object_id'),
            'coordinates'=>array(self::HAS_MANY, 'ObjectCoordinates', 'object_id'),
		);
	}

    /*
     * Das ist ein Wordaround, damit die JSON Serialisierung funktioniert
     * TODO: Checken, ob DB noch läuft
     */
    public function getAttributes($names = true) {
        $arr = parent::getAttributes($names);
        $arr['attributes'] = $this->attributes;
        $arr['coordinates'] = $this->coordinates;
        return $arr;
    }


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'name' => 'Name',
			'description' => 'Description',
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
        $criteria->compare('type',$this->type,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
	}

    public function findAllByBBox($lat1, $lat2, $lng1, $lng2)
    {
        Yii::trace(get_class($this).'.findAllByBBox()','system.db.ar.CActiveRecord');

        $criteria=$this->getDbCriteria();
        $criteria->addBetweenCondition('coordinates.lng', $lat1, $lat2);
        $criteria->addBetweenCondition('coordinates.lat', $lng1, $lng2);

        return $this->getActiveFinder($criteria->with)->query($criteria,true);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Object the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
