<?php
/**
* BUM Module class file.
* Basic User Management - Provides management of the users.
*
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
*/
/**
* BumModule class
* @package		bum
*/

class BumModule extends CWebModule
{
	/**
     * @var boolead
     * If this is true than the module is in installation mode.
     */
    public $install = false;
    
	/**
     * @var integer
     * How many hours the activation link si active. 
     * After this amount of time the acount is not activated, but is deleted instead... 
     */
    public $hoursActivationLinkIsActive = 72;
    
	/**
     * @var string
     * From whom the confirmation email is sent? (for sign up)
     */
    public $notificationSignUpEmail = 'webmaster@localhost';
    
	/**
     * @var integer
     * How many hours the verification link si active. 
     * After this amount of time the email is deleted rather than confirmed... 
     */
    public $hoursVerificationLinkIsActive = 144;
    
	/**
     * @var boolean
     * If SignUp is base on invitations. Default value: false.
     */
    public $invitationBasedSignUp = false;
    
	/**
     * @var boolean
     * Display the invite button. Default value: true.
     */
    public $invitationButtonDisplay = true;
    
	/**
     * @var integer
     * Default number of invitations
     */
    public $invitationDefaultNumber = 5;
    
	/**
     * @var boolean
     * If SignUp is disabled, no signUps are alowed in any case!
     */
    public $enabledSignUp = true;
    
	/**
     * @var string
     * From where the invitation email is sent? (for invitations)
     */
    public $invitationEmail = 'webmaster@localhost';
    
	/**
     * @var integer
     * How many hours the invitation link si active. 
     * After this amount of time the email is deleted rather than confirmed... 
     */
    public $hoursInvitationLinkIsActive = 144;
    
	/**
     * @var string
     * From where the verification email is sent? (for email verification)
     */
    public $notificationVerificationEmail = 'webmaster@localhost';
    
	/**
     * @var boolean
     * If a user has not verified it's account yet, he/she is still able to log in
     */
    public $logInIfNotVerified = false;
    
	/**
     * @var boolean
        * If operatinos like save and delete are allowed. $demoMode = true => no savings, updates and no deletions are allowed
     */
    public $demoMode = false;
    
	/**
     * @var string
     * Password recovery email is sent from:
     */
    public $passwordRecoveryEmail = 'webmaster@localhost';
    
	/**
     * @var integer
     * How many hours the password recovery request is active?
     */
    public $hoursPasswordRecoveryLinkIsActive = 10;
    
	/**
     * @var boolean
        * If it's true, than if a password recovery request expire, it is not deleted, but it's property "expired" is set to true. So in the database remain all password requests that have been made.
     */
    public $trackPasswordRecoveryRequests = false;
    
    /**
     * @var bool
     * If the triggers from the database are active and used. 
     */
    public $db_triggers = true;

    // getAssetsUrl()
    //    return the URL for this module's assets, performing the publish operation
    //    the first time, and caching the result for subsequent use.
    private $_assetsUrl;

    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('bum.assets') );
        return $this->_assetsUrl;
    }
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'bum.models.*',
			'bum.components.*',
		));
        
        if (!Yii::app()->hasComponent('mail') || get_class(Yii::app()->getComponent('mail')) != "YiiMail") {
            Yii::app()->user->setFlash('notice module-absent-yii_mail', "Extension yii-mail is required in order for {$this->id} module to properly function.");
            //throw new CException('Extension yii-mail is required in order for ' . $this->id . ' module to properly function.');
        }
        
        if ($this->install) {
            Yii::app()->user->setFlash('notice install-on', "After instalation is complete please set install property to false.");
        }
        
        BumSettings::checkInitSettings($this); // check and set the settings 
        // => not working => infinite loop;

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
