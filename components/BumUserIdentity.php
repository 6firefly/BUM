<?php
/**
 * BumUserIdentity class file.
 * Identify uniquely a user.
 *
 * Credits: Based on the original yii framework UserIdentity.php file.
 * 
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
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
    const ERROR_SOCIAL_PROVIDER_INVALID = 4;
    const ERROR_NO_SOCIAL_LOG_IN_IN_DEMO = 5;
    
    private $_id;
    
    public $social_provider;
    public $social_user_id;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
        $numargs = func_num_args();
        if($numargs < 2 ) {
            $social = func_get_arg(0);
            
            $this->social_provider = $social['provider'];
            $this->social_user_id = $social['user_id'];
            
        }else{
            $this->username=func_get_arg(0);
            $this->password=func_get_arg(1);
        }
	}
    
    public function socialAuthenticate(){
                    
        switch($this->social_provider){
            case 'facebook':
                $usersData = UsersData::model()->findByAttributes(array('facebook_user_id' => $this->social_user_id));
                break;
            case 'twitter':
                $usersData = UsersData::model()->findByAttributes(array('twitter_user_id' => $this->social_user_id));
                break;
        }
        
        if($usersData){
            $user = Users::model()->findByPk($usersData->id);
            if($user){ // this should always work
                $this->_id = $user->id;
                $this->username = $user->user_name;

                if (NULL == $user->date_of_last_access) {
                    $lastLogin = time();
                } else {
                    $lastLogin = strtotime($user->date_of_last_access);
                }

                $this->setState('dateOfLastAccess', $lastLogin);
                $this->errorCode = self::ERROR_NONE;
            }else{
                // shouldn't reach here
            }
        }else{
            if(!Yii::app()->getModule('bum')->demoMode){
                // user is not found in the database; create one
                // then ask the new user for a username an address and a password...
                switch($this->social_provider){
                    case 'facebook':
                            $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+{}[];:",./<>?';

                            $user = new Users;
                            $user->user_name = $this->social_provider . ' ' . uniqid('');
                            $user->email = uniqid('') . "@noEmail.com"; // Primary Email cannot be blank.
                            $user->active = 1;
                            $user->password = substr(str_shuffle($letters), 0, 15);
                            $user->password_repeat = $user->password;
                            $user->status = Users::STATUS_ONLY_FACEBOOK;

                            if($user->save()){ // should work

                                $user->user_name = $this->social_provider . '[' . $user->id . ']';
                                $user->email = $user->id . "@noEmail.com";
                                $user->password = $user->password_repeat = ''; // because old password is not changed if new password is empty
                                $user->save(); // to have a shorter user name and email address; id there is one error, old values remain

                                $usersData = new UsersData;
                                $usersData->id = $user->id;
                                $usersData->facebook_user_id = $this->social_user_id;
                                $usersData->activation_code = sha1(mt_rand(1, 99999).time());

                                if($usersData->save()){ // should work
                                    $this->_id = $user->id;
                                    $this->username = $user->user_name;

                                    if (NULL == $user->date_of_last_access) {
                                        $lastLogin = time();
                                    } else {
                                        $lastLogin = strtotime($user->date_of_last_access);
                                    }

                                    $this->setState('dateOfLastAccess', $lastLogin);
                                    $this->errorCode = self::ERROR_NONE;                                
                                }
                            }

                        break;
                    case 'twitter':
                            $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+{}[];:",./<>?';

                            $user = new Users;
                            $user->user_name = $this->social_provider . ' ' . uniqid('');
                            $user->email = uniqid('') . "@noEmail.com"; // Primary Email cannot be blank.
                            $user->active = 1;
                            $user->password = substr(str_shuffle($letters), 0, 15);
                            $user->password_repeat = $user->password;
                            $user->status = Users::STATUS_ONLY_TWITTER;

                            if($user->save()){ // should work

                                $user->user_name = $this->social_provider . '[' . $user->id . ']';
                                $user->email = $user->id . "@noEmail.com";
                                $user->password = $user->password_repeat = ''; // because old password is not changed if new password is empty
                                $user->save(); // to have a shorter user name and email address; id there is one error, old values remain

                                $usersData = new UsersData;
                                $usersData->id = $user->id;
                                $usersData->twitter_user_id = $this->social_user_id;
                                $usersData->activation_code = sha1(mt_rand(1, 99999).time());

                                if($usersData->save()){ // should work
                                    $this->_id = $user->id;
                                    $this->username = $user->user_name;

                                    if (NULL == $user->date_of_last_access) {
                                        $lastLogin = time();
                                    } else {
                                        $lastLogin = strtotime($user->date_of_last_access);
                                    }

                                    $this->setState('dateOfLastAccess', $lastLogin);
                                    $this->errorCode = self::ERROR_NONE;                                
                                }
                            }

                        break;
                    default :
                            $this->errorCode = self::ERROR_SOCIAL_PROVIDER_INVALID;
                        break;
                }
            }else{
                $this->errorCode = self::ERROR_NO_SOCIAL_LOG_IN_IN_DEMO;
            }
        }

		return !$this->errorCode;
    }
    
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
                } else {
                    $this->_id = $user->id;
                    $this->username = $user->user_name;
                    
                    if (NULL == $user->date_of_last_access) {
                        $lastLogin = time();
                    } else {
                        $lastLogin = strtotime($user->date_of_last_access);
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