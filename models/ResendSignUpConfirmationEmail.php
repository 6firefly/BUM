<?php
/**
 * ResendSignUpConfirmationEmail class file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * ResendSignUpConfirmationEmail class.
 * @package		bum
 * 
 * The form for resending sign up confirmation email.
 */
class ResendSignUpConfirmationEmail extends CFormModel
{
	public $email_or_user_name;

	public $verifyCode; // property required by Captcha
    public $usersData; // the user to which to resend the confirmation email
    
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// email_or_user_name address is requered
			array('email_or_user_name', 'required'),
            
            // set a maximum lenght (base on table.field max length)
            array('email_or_user_name', 'length', 'max'=>60),
            
            // see if the account has not been yet activated...
            array('email_or_user_name', 'existsAndIsNotActivated'),
            
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()), // see: http://www.yiiframework.com/forum/index.php/topic/21561-captcha-custom-validation/ for information
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Email',
            'user_name'=>'User Name',
            'verifyCode' => 'Verification Code',
		);
	}
    
    /**
     * Check if the account is active or not. If the account is active the activation email can not be sent again.. 
     * @param type $attribute
     * @param type $params
     */
    public function existsAndIsNotActivated($attribute,$params){
        // try to find user by username
        $user = Users::model()->findByAttributes(array('user_name' => $this->$attribute));
        if ($user === NULL) {
            // try to find user by its email address...
            $user = Users::model()->findByAttributes(array('email' => $this->$attribute));
        }
        if ($user === NULL) {
            $this->addError($attribute, 'No data found!');
        }else{
            if ($user->active) {
                // "No data found!" error message, in order not to prevent checking for email addresses.
                $this->addError($attribute, 'No data found!');
            }else{
                $this->usersData = UsersData::model()->findByPk($user->id);
                if ($this->usersData === NULL) {
                    // should not enter here
                    // "No data found!" error message, in order not to prevent checking for email addresses.
                    $this->addError($attribute, 'No data found!');
                }
            }
        }
    }    
}
