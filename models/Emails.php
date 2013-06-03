<?php
/**
 * Emails class file.
 * Model class file for table emails.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * Emails  class.
 * @package		bum
 */
/**
 * This is the model class for table "emails".
 *
 * The followings are the available columns in table 'emails':
 * @property string $id
 * @property string $id_user
 * @property string $name
 * @property boolean $verified
 * @property string $verification_code
 * @property string $date_of_creation
 * @property boolean $visible
 *
 * The followings are the available model relations:
 * @property Users $idUser
 */
class Emails extends BumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Emails the static model class
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
		return 'emails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('name', 'email'),
			array('id_user, name', 'required'),
            array('verified, visible', 'boolean'),
            
			array('name', 'length', 'max'=>60),
			array('verification_code', 'length', 'max'=>45),
			array('verified, visible', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			// array('id, id_user, name, verified, verification_code, date_of_creation, visible', 'safe', 'on'=>'search'), // actually for now there is no search facility for this model
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
     * Check if a user has unverified emails.
     * @param type $id_user
     * @return boolean
     */
    public static function hasUnverifiedEmails($id_user){
        $model = self::model()->findAllByAttributes(array('id_user'=>$id_user, 'verified'=>false, 'visible'=>true));
        if (count($model)>0) {
            return true;
        }else{
            return false;
        }
    }

    
    /**
     * Find all emails of one user.
     * @param type $id_user
     * @return \CActiveDataProvider
     */
    public static function findMyEmails($id_user){
        $EmailsCriteria=new CDbCriteria;
        $EmailsCriteria->compare('visible', true);
        $EmailsCriteria->compare('id_user', $id_user, false);

        $myEmails =new CActiveDataProvider('Emails', array(
            'criteria'=>$EmailsCriteria
        ));                
        
        return $myEmails;
    }
    
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'name' => 'New Email',
			'verified' => 'Verified',
			'verification_code' => 'Verification Code',
			'date_of_creation' => 'Date Of Creation',
			'visible' => 'Visible',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	/*public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,false);
		$criteria->compare('id_user',$this->id_user,false);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('verified',$this->verified, false);
		$criteria->compare('verification_code',$this->verification_code,true);
		$criteria->compare('date_of_creation',$this->date_of_creation,true);
		$criteria->compare('visible',$this->visible, false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}*/
    
    /**
     * Update some datatime statistical fields.
     */
    public function beforeSave() {
        if(!Yii::app()->getModule('bum')->db_triggers){
            if($this->isNewRecord){
                $this->date_of_creation = new CDbExpression('NOW()');
            }
        }
        return parent::beforeSave();
    }    
}