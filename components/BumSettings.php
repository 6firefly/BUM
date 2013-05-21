<?php
/**
 * BumSettings class file.
 * Init/Load the default BUM module settings.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * BumSettings helper class.
 * @package		bum
 */
class BumSettings 
{
    static $connection;
    
    private static function initSetting($module, $setting){
        if(!isset(self::$connection)) {
            self::$connection=Yii::app()->db;
        }

        $command = self::$connection->createCommand("SELECT value, setting_order FROM settings WHERE name = '{$setting['name']}'");
        $row=$command->queryRow();
        if(!$row){
            // create setting and init it to it's default value
            $sql="INSERT INTO settings (name, value, label, description, setting_order) VALUES(:name, :value, :label, :description, :setting_order)";

            $setting['value'] = $module->$setting['name'];
            
            $command = self::$connection->createCommand($sql);
            $command->bindParam(":name", $setting['name'], PDO::PARAM_STR);
            $command->bindParam(":value", $setting['value'], PDO::PARAM_STR);
            $command->bindParam(":label", $setting['label'], PDO::PARAM_STR);
            $command->bindParam(":description", $setting['description'], PDO::PARAM_STR);
            $command->bindParam(":setting_order",  $setting['setting_order'], PDO::PARAM_INT);
            $command->execute();
        }else{
            $module->$setting['name'] = $row["value"];

            if ($row["setting_order"] != $setting['setting_order']){
                $sql="UPDATE settings SET setting_order = :setting_order WHERE name = '{$setting['name']}'";
                
                $command = self::$connection->createCommand($sql);
                $command->bindParam(":setting_order",  $setting['setting_order'], PDO::PARAM_INT);
                $command->execute();
            }
        }
    }
    
    /**
     * Check if all settings are setted and init them if not..
     */
    public static function checkInitSettings($module){ 
        // $module = Yii::app()->getModule('bum'); // => infinite loop... :((
            
        // init logInIfNotVerified property
        // $settings = Settings::model()->findByAttributes(array('name'=>'logInIfNotVerified')); // ActiveRecord => infinite loop
        $setting = array(
            'name' => 'logInIfNotVerified',
            'label' => 'Allow users to LogIn if they are not active?',
            'description' => '',
            'setting_order' => 100,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////

        // init enabledSignUp property
        $setting = array(
            'name' => 'enabledSignUp',
            'label' => 'SignUp is enabled?',
            'description' => 'If SignUp is disabled, no SignUps are allowed, in any case!',
            'setting_order' => 200,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////

        // init invitationBasedSignUp property
        $setting = array(
            'name' => 'invitationBasedSignUp',
            'label' => 'Only invited users are allowed to SignUp?',
            'description' => 'If SignUp is disabled, no user can SignUp, even invited ones!',
            'setting_order' => 300,
        );

        self::initSetting($module, $setting);

        // init invitationButtonDisplay property
        $setting = array(
            'name' => 'invitationButtonDisplay',
            'label' => 'Display the invitation button to all users?',
            'description' => '',
            'setting_order' => 400,
        );

        self::initSetting($module, $setting);

        // init invitationDefaultNumber property
        $setting = array(
            'name' => 'invitationDefaultNumber',
            'label' => 'Default number of invitations per user? (if <0 = infinit number)',
            'description' => '',
            'setting_order' => 500,
        );

        self::initSetting($module, $setting);

        // init invitationEmail property
        $setting = array(
            'name' => 'invitationEmail',
            'label' => 'Invitation email is sent from:',
            'description' => '',
            'setting_order' => 600,
        );

        self::initSetting($module, $setting);

        // init hoursInvitationLinkIsActive property
        $setting = array(
            'name' => 'hoursInvitationLinkIsActive',
            'label' => 'How many hours the invitation link is active? (if <0 = forever)',
            'description' => '',
            'setting_order' => 700,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////

        // init notificationSignUpEmail property
        $setting = array(
            'name' => 'notificationSignUpEmail',
            'label' => 'Activation email is sent from:',
            'description' => '',
            'setting_order' => 800,
        );

        self::initSetting($module, $setting);

        // init hoursActivationLinkIsActive property
        $setting = array(
            'name' => 'hoursActivationLinkIsActive',
            'label' => 'How many hours the activation link is active? (if <0 = forever)',
            'description' => '',
            'setting_order' => 900,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////

        // init hoursVerificationLinkIsActive property
        $setting = array(
            'name' => 'hoursVerificationLinkIsActive',
            'label' => 'How many hours the email verification link is active? (if <0 = forever)',
            'description' => 'How many hours the email verification link is active? (when user associates a new email address to his/hers account)',
            'setting_order' => 1000,
        );

        self::initSetting($module, $setting);

        // init notificationVerificationEmail property
        $setting = array(
            'name' => 'notificationVerificationEmail',
            'label' => 'Verification email is sent from:',
            'description' => '',
            'setting_order' => 1100,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////
        //
        // init passwordRecoveryEmail property
        $setting = array(
            'name' => 'passwordRecoveryEmail',
            'label' => 'Password recovery email is sent from:',
            'description' => '',
            'setting_order' => 1200,
        );

        self::initSetting($module, $setting);

        // init hoursPasswordRecoveryLinkIsActive property
        $setting = array(
            'name' => 'hoursPasswordRecoveryLinkIsActive',
            'label' => 'How many hours the password recovery request is active?',
            'description' => '',
            'setting_order' => 1300,
        );

        self::initSetting($module, $setting);

        // init trackPasswordRecoveryRequests property
        $setting = array(
            'name' => 'trackPasswordRecoveryRequests',
            'label' => 'Track password recovery requests?',
            'description' => 'If it\'s true, than if a password recovery request expire, it is not deleted, but it\'s property "expired" is set to true. So in the database remain all password requests that have been made.',
            'setting_order' => 1400,
        );

        self::initSetting($module, $setting);
        ///////////////////////////////
    }
}