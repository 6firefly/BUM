<?php
/**
 * LoginForm class file.
 *
 * Credits: Based on the original yii framework LoginForm.php file.
 * 
 * @package		bum
 */
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
            'username'=>'Email or User Name'
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors()){
            
			$this->_identity=new BumUserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate()){
                switch($this->_identity->errorCode){
                    case BumUserIdentity::ERROR_USER_NOT_ACTIVE:
                        $this->addError('username','Please activate your account! ' . CHtml::link('Resend confirmation email!', array('users/resendSignUpConfirmationEmail')) . '' );
                        break;
                    default:
                        $this->addError('password','Incorrect username or password.');
                        break;
                }
            }
		}
	}

	/**
	 * Logs in the user using the given provider id
	 * @return boolean whether login is successful
	 */
	public function socialLogin($social)
	{
		if($this->_identity===null)
		{
			$this->_identity=new BumUserIdentity($social);
			$this->_identity->socialAuthenticate();
		}
		if($this->_identity->errorCode===BumUserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
            
            // update user last login time
            Users::model()->updateByPk($this->_identity->id, array('date_of_last_access' => new CDbExpression('NOW()')));
            
			return true;
		}
		else{
			return false;
        }
	}
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new BumUserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===BumUserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
            
            // update user last login time
            Users::model()->updateByPk($this->_identity->id, array('date_of_last_access' => new CDbExpression('NOW()')));
            
			return true;
		}
		else{
			return false;
        }
	}
}
