<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $group_id
 * @property string $last_activity
 * @property integer $blocked
 * @property string $language_code
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, password, email, last_activity', 'required'),
			array('blocked', 'numerical', 'integerOnly'=>true),
			array('name, Password', 'length', 'max'=>64),
			array('email', 'length', 'max'=>255),
			array('group_id, last_activity', 'length', 'max'=>10),
			array('language_code', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, group_id, blocked, language_code', 'safe', 'on'=>'search'),
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
            'group' => array(self::HAS_ONE, 'group', 'group_id'),
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
			'password' => 'Password',
			'email' => 'E Mail',
			'group_id' => 'Group',
			'last_activity' => 'Last Activity',
			'blocked' => 'Blocked',
			'language_code' => 'Language Code',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('blocked',$this->blocked);
		$criteria->compare('language_code',$this->language_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function hashPassword($password) {
        $hash = $password;
        $pepper = Yii::app()->params['pepper'];
        for ($i = 0; $i < 20; $i++) {
            $hash = md5($pepper . $hash . sha1($pepper . $hash) . md5($hash));
        }
        return $hash;
    }
}
