<?php
/**
 * Invitations class file.
 * Model class file for table invitations.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * Invitations  class.
 * @package		bum
 */

/**
 * This is the model class for table "invitations".
 *
 * The followings are the available columns in table 'invitations':
 * @property string $id
 * @property string $id_user
 * @property string $email
 * @property string $invitation_code
 * @property string $date_of_invitation_send
 * @property string $date_of_invitation_accepted
 *
 * The followings are the available model relations:
 * @property Users $idUser
 */
class Invitations extends BumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invitations the static model class
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
		return 'invitations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'email', 'on'=>'create'),
			array('email', 'emailUsersUQ', 'on'=>'create'),
            
			array('id_user, email, invitation_code', 'required', 'on'=>'create'),
			array('email', 'length', 'max'=>60, 'on'=>'create'),
            
			array('invitation_code', 'length', 'max'=>10),
            
			array('note', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_user, email, invitation_code, date_of_invitation_send, date_of_invitation_accepted', 'safe', 'on'=>'search'),
		);
	}
    
    /**
     * check if the email is not of an active member (primary email only)
     * check the invitation was not already sent by this user
     * @param type $attribute
     * @param type $params
     */
    public function emailUsersUQ($attribute, $params)
    {
        //check if the email is not of an active member (primary email only)
        $count = Users::model()->countByAttributes(array('email'=>$this->$attribute, 'active'=>true));
        
        if(strlen($count) != 1 || (int)$count != 0){
            $this->addError($attribute, 'Please try another email address!');
            return;
        }

        // check the invitation was not already sent by this user
        $count = Invitations::model()->countByAttributes(array('email'=>$this->$attribute, 'id_user'=>Yii::app()->user->id));
        
        if(strlen($count) != 1 || (int)$count !== 0){
            $this->addError($attribute, 'You had already invited this person; please try another email address!');
            return;
        }

    }    

    /**
     * Validate invitation_code: check if the email address coresponde to this invitation code; check if the invitation code exists and has not been used.
     * @param type $attribute
     * @param type $params
     */
    public function afterValidate(){ 
        // run this validation only for sign up scenario and if validation is needed (sign up is invitatin based)
        if ($this->scenario == 'signUp' && Yii::app()->getModule('bum')->invitationBasedSignUp) {
            $invitationCheck = Invitations::model()->countByAttributes(array('invitation_code'=>$this->invitation_code)); 

            // if invitation not found; 
            if($invitationCheck == 0){
                $this->addError('invitation_code', 'Invitation code is invalide!');
                return;
            }
            unset($invitationCheck);

            $invitationCheck = Invitations::model()->countByAttributes(array('invitation_code'=>$this->invitation_code), array('condition'=>"UPPER(email) = :email", 'params'=>array(":email"=>strtoupper($this->email)))); 

            // if invitation_code is not associated with this email
            if($invitationCheck == 0){
                $this->addError('invitation_code', 'Invitation code do not match provided email address!');
                return;
            }
            unset($invitationCheck);

            $invitationCheck = Invitations::model()->countByAttributes(array('invitation_code'=>$this->invitation_code, 'date_of_invitation_accepted'=>NULL), array('condition'=>"UPPER(email) = :email", 'params'=>array(":email"=>strtoupper($this->email)))); 
            // if invitation_code has been used
            if($invitationCheck == 0){
                $this->addError('invitation_code', 'Invitation code already used!');
                return;
            }
            unset($invitationCheck);
        }
    }
    
    /**
     * Find the model and update it before save it. Only for sign up scepario.
     */
    function beforeSave() {
        if ($this->scenario == 'signUp') {
            $invitationCheck = Invitations::model()->findByAttributes(array('invitation_code'=>$this->invitation_code, 'date_of_invitation_accepted'=>NULL), array('condition'=>"UPPER(email) = :email", 'params'=>array(":email"=>strtoupper($this->email)))); 
            // in invitation found
            
            if($invitationCheck){
                $this->attributes = $invitationCheck->attributes;
                $this->id = $invitationCheck->id;
                $this->id_user = $invitationCheck->id_user;
                $this->date_of_invitation_accepted = new CDbExpression('NOW()');
            }else{
                // afterValidation() should prevent this in case of invitationBasedSignUp = true
                return true; // do not save; nothing th save
            }
            unset($invitationCheck);
        }
        
        if(!Yii::app()->getModule('bum')->db_triggers){
            if($this->isNewRecord){
                $this->date_of_invitation_send = new CDbExpression('NOW()');
            }
        }
        
        return parent::beforeSave();
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
			'idUserInvited' => array(self::BELONGS_TO, 'Users', 'id_user'),
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
			'id_user_invited' => 'Id Invited User',
			'email' => 'Email',
            'note' => 'Note',
			'invitation_code' => 'Invitation Code',
			'date_of_invitation_send' => 'Send Date',
			'date_of_invitation_accepted' => 'Accepted Date',
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
		$criteria->compare('email',$this->email,true);
        $criteria->compare('note',$this->note,true);
		$criteria->compare('invitation_code',$this->invitation_code,true);
		$criteria->compare('date_of_invitation_send',$this->date_of_invitation_send,true);
		$criteria->compare('date_of_invitation_accepted',$this->date_of_invitation_accepted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
}