<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property string $name
 * @property double $start_lat
 * @property double $start_lng
 * @property double $end_lat
 * @property double $end_lng
 * @property array $presets
 */
class Event extends CActiveRecord
{

    public $preset;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, start_lat, start_lng, end_lat, end_lng', 'required'),
			array('start_lat, start_lng, end_lat, end_lng', 'numerical'),
			array('name', 'length', 'max'=>64),
            array('preset','type','type'=>'array','allowEmpty'=>false),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, start_lat, start_lng, end_lat, end_lng', 'safe', 'on'=>'search'),
		);
	}

    public function afterSave(){
        parent::afterSave();
        EventPreset::model()->deleteAllByAttributes(array('event_id'=>$this->id));
        foreach($this->preset as $presetId){
            $preset = Preset::model()->findByPk($presetId);
            if(!$preset)
                continue;

            $ep = new EventPreset('insert');
            $ep->event_id = $this->id;
            $ep->preset_id = $presetId;
            $ep->save();
        }
    }

    public function afterFind(){
        parent::afterFind();
        $this->preset = array_values(CHtml::listData($this->presets,'id','id'));
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'presets'=>array(self::MANY_MANY, 'Preset', 'event_preset(event_id,preset_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'start_lat' => 'Start Lat',
			'start_lng' => 'Start Lng',
			'end_lat' => 'End Lat',
            'end_lng' => 'End Lng',
            'preset' => 'Presets',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('start_lat',$this->start_lat);
		$criteria->compare('start_lng',$this->start_lng);
		$criteria->compare('end_lat',$this->end_lat);
		$criteria->compare('end_lng',$this->end_lng);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Event the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
