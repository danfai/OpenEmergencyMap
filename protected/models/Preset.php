<?php

/**
 * This is the model class for table "preset".
 *
 * The followings are the available columns in table 'preset':
 * @property integer $id
 * @property string $type
 * @property string $name
 */
class Preset extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'preset';
	}

    public function beforeValidate(){
        $result = parent::beforeValidate();
        if($result && is_array($this->type))
            $this->type = join(',',$this->type);

        if(sizeof($this->type) < 1){
            $this->addError('type','Please set at least one type');
            $result=false;
        }
        return $result;
    }

    public function afterFind(){
        parent::afterFind();
        $this->type = preg_split('/,/',$this->type);
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('type', 'in', 'range'=>array('marker','polyline','polygon'), 'allowEmpty'=>false),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, name', 'safe', 'on'=>'search'),
		);
	}

    public function scopes() {
        return array(
            'grouped'=>array(
                'group'=>'type'
            )
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
			'id' => 'ID',
			'name' => 'Name',
			'type' => 'Typ',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ObjectAttributes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
