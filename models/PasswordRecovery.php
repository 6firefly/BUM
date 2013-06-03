<?php
/**
 * PasswordRecovery class file.
 * Model class file for table password_recovery.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * PasswordRecovery  class.
 * @package		bum
 */

/**
 * This is the model class for table "password_recovery".
 *
 * The followings are the available columns in table 'password_recovery':
 * @property string $id
 * @property string $id_user
 * @property string $code
 * @property string $code_inserted
 * @property string $long_code
 * @property string $user_name
 * @property string $email
 * @property string $ip
 * @property integer $used
 * @property string $date_of_request
 *
 * The followings are the available model relations:
 * @property Users $idUser
 */
class PasswordRecovery extends BumActiveRecord
{
    // compare inserted code with code from the database, for the specific request
    public $code_inserted;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PasswordRecovery the static model class
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
		return 'password_recovery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('code_inserted', 'compare', 'compareAttribute'=>'code', 'message'=>'Invalide code, please check validation code and try again.', 'on'=>'askCode'),
            
			array('id_user, code, long_code, email, ip', 'required'),
			array('used', 'numerical', 'integerOnly'=>true),
			array('id_user', 'length', 'max'=>20),
			array('code', 'length', 'max'=>10),
			array('long_code', 'length', 'max'=>32),
			array('user_name', 'length', 'max'=>45),
			array('email', 'length', 'max'=>60),
			array('ip', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, code, long_code, user_name, email, ip, used, date_of_request', 'safe', 'on'=>'search'),
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
			'idUser' => array(self::BELONGS_TO, 'Users', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'code' => 'Code',
			'long_code' => 'Long Code',
			'user_name' => 'User Name',
			'email' => 'Email',
			'ip' => 'Ip',
			'used' => 'Used',
			'date_of_request' => 'Date Of Request',
            'code_inserted'=>'Email Validation Code'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('long_code',$this->long_code,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('used',$this->used);
		$criteria->compare('date_of_request',$this->date_of_request,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    /**
     * Update some datatime statistical fields.
     */
    public function beforeSave() {
        if(!Yii::app()->getModule('bum')->db_triggers){
            if($this->isNewRecord){
                $this->date_of_request = new CDbExpression('NOW()');
            }
        }
        return parent::beforeSave();
    }    
}