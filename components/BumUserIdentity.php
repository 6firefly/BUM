<?php
/**
 * BumUserIdentity class file.
 * Identify uniquely a user.
 *
 * Credits: Based on the original yii framework UserIdentity.php file.
 * 
 * @package		bum
 */
/**
 * BumUserIdentity helper class.
 * @package		bum
 * 
 * BumUserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class BumUserIdentity extends CUserIdentity
{
	const ERROR_USER_NOT_ACTIVE=3;
    
    private $_id;

    /**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        // try to find user by username
        $user = Users::model()->findByAttributes(array('user_name' => $this->username));
        if ($user === NULL) {
            // try to find user by its email address...
            $user = Users::model()->findByAttributes(array('email' => $this->username));
        }
        
        if ($user === NULL) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            
            if (Yii::app()->getModule('bum')->logInIfNotVerified) {
                $hasLogINRight = true;
            }else{
                $hasLogINRight = $user->isActive();
            }
            
            if ($hasLogINRight) {
                if (!$user->validatePassword($this->password)) {
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                }
                else {
                    $this->_id = $user->id;
                    
                    if (NULL == $user->date_of_last_access) {
                        $lastLogin = time();
                    } else {
                        $lastLogin = strtotime($user->date_of_last_access);
                    }

                    if (!$user->isActive()) {
                        Yii::app()->user->setFlash('notice', "Please activate your account! " . CHtml::link('Resend confirmation email!', array('users/resendSignUpConfirmationEmail')) );                        
                    }
                    
                    $this->setState('dateOfLastAccess', $lastLogin);
                    $this->errorCode = self::ERROR_NONE;
                }
            }else{
                $this->errorCode = self::ERROR_USER_NOT_ACTIVE;
            }

        }
        
		return !$this->errorCode;
	}
    
    /**
     * Return the current user id
     * @return type
     */
	public function getId() {
		return $this->_id;
	}
}