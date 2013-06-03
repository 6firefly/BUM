<?php
/**
 * Settings class file.
 * Model class file for table settings.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * Settings  class.
 * @package		bum
 */
/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property string $id
 * @property string $name
 * @property string $value
 * @property string $date_of_update
 */
class Settings extends BumActiveRecord
{
    CONST INVITATION_BASED_NO=0;
    CONST INVITATION_BASED_YES=1;

    CONST INVITATION_DISPLAY_BUTTON_NO=0;
    CONST INVITATION_DISPLAY_BUTTON_YES=1;
    
    CONST LOGIN_IF_NOT_VERIFIED_NO=0;
    CONST LOGIN_IF_NOT_VERIFIED_YES=1;
    
    CONST SIGNUP_DISENABLED=0;
    CONST SIGNUP_ENABLED=1;
    
    CONST TRACKPASSWORDRECOVERY_DISENABLED=0;
    CONST TRACKPASSWORDRECOVERY_ENABLED=1;
        
    
    /**
     * @return type 
     */
    public static function getTrackPasswordRecoveryRequestsOptions()
    {
        // because for false - 0 the attriburte active is empty, and the first one is selected...
        return array(
            self::TRACKPASSWORDRECOVERY_DISENABLED => 'No',
            self::TRACKPASSWORDRECOVERY_ENABLED => 'Yes',
        );
    }
    
    /**
     * @return type 
     */
    public static function getSignUpEnabledOptions()
    {
        // because for false - 0 the attriburte active is empty, and the first one is selected...
        return array(
            self::SIGNUP_DISENABLED => 'No',
            self::SIGNUP_ENABLED => 'Yes',
        );
    }
    
    /**
     * @return type 
     */
    public static function getInvitationBasedOptions()
    {
        // because for false - 0 the attriburte active is empty, and the first one is selected...
        return array(
            self::INVITATION_BASED_NO => 'No',
            self::INVITATION_BASED_YES => 'Yes',
        );
    }
    
    /**
     * @return type 
     */
    public static function getInvitationDisplayButtonOptions()
    {
        // because for false - 0 the attriburte active is empty, and the first one is selected...
        return array(
            self::INVITATION_DISPLAY_BUTTON_NO => 'No',
            self::INVITATION_DISPLAY_BUTTON_YES => 'Yes',
        );
    }
    
    /**
     * @return type 
     */
    public static function getLogInIfNotVerifiedOptions()
    {
        // because for false - 0 the attriburte active is empty, and the first one is selected...
        return array(
            self::LOGIN_IF_NOT_VERIFIED_NO => 'No',
            self::LOGIN_IF_NOT_VERIFIED_YES => 'Yes',
        );
    }
    

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Settings the static model class
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
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, value, label', 'required'),
			array('name, value', 'length', 'max'=>45),
			array('label', 'length', 'max'=>80),
			array('date_of_update', 'safe'),
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
			'value' => 'Value',
			'label' => 'Label',
			'description' => 'Description',
			'date_of_update' => 'Date Of Update',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('date_of_update',$this->date_of_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    /**
     * Update some datatime statistical fields.
     */
    public function beforeSave() {
        if(!Yii::app()->getModule('bum')->db_triggers){
            if(!$this->isNewRecord){
                $this->date_of_update = new CDbExpression('NOW()');
            }
        }
        return parent::beforeSave();
    }    
}