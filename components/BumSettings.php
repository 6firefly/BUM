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
    /**
     * Check if all settings are setted and init them if not..
     */
    public static function checkInitSettings(){ 
        $module = Yii::app()->getModule('bum'); // => infinite loop... :((
        if( $module->install ){
            // init logInIfNotVerified property
            $settings = Settings::model()->findByAttributes(array('name'=>'logInIfNotVerified'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'logInIfNotVerified';
                $settings->value = ($module->logInIfNotVerified)?"1":"0";
                $settings->label = "Allow users to LogIn if they are not active?";
                $settings->description = "";
                $settings->save();
            }
            $module->logInIfNotVerified = $settings->value;
            unset($settings);
            ///////////////////////////////

            // init enabledSignUp property
            $settings = Settings::model()->findByAttributes(array('name'=>'enabledSignUp'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'enabledSignUp';
                $settings->value = ($module->enabledSignUp)?"1":"0";
                $settings->label = "SignUp is enabled?";
                $settings->description = "If SignUp is disabled, no SignUps are allowed, in any case!";
                $settings->save();
            }
            $module->enabledSignUp = $settings->value;
            unset($settings);
            ///////////////////////////////

            // init invitationBasedSignUp property
            $settings = Settings::model()->findByAttributes(array('name'=>'invitationBasedSignUp'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'invitationBasedSignUp';
                $settings->value = ($module->invitationBasedSignUp)?"1":"0";
                $settings->label = "Only invited users are allowed to SignUp?";
                $settings->description = "If SignUp is disabled, no user can SignUp, even invited ones!";
                $settings->save();
            }
            $module->invitationBasedSignUp = $settings->value;
            unset($settings);

            // init invitationButtonDisplay property
            $settings = Settings::model()->findByAttributes(array('name'=>'invitationButtonDisplay'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'invitationButtonDisplay';
                $settings->value = ($module->invitationButtonDisplay)?"1":"0";
                $settings->label = "Display the invitation button to all users?";
                $settings->description = "";
                $settings->save();
            }
            $module->invitationButtonDisplay = $settings->value;
            unset($settings);

            // init invitationDefaultNumber property
            $settings = Settings::model()->findByAttributes(array('name'=>'invitationDefaultNumber'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'invitationDefaultNumber';
                $settings->value = $module->invitationDefaultNumber;
                $settings->label = "Default number of invitations per user? (if <0 = infinit number)";
                $settings->description = "";
                $settings->save();
            }
            $module->invitationDefaultNumber = $settings->value;
            unset($settings);

            // init invitationEmail property
            $settings = Settings::model()->findByAttributes(array('name'=>'invitationEmail'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'invitationEmail';
                $settings->value = $module->invitationEmail;
                $settings->label = "Invitation email is sent from:";
                $settings->description = "";
                $settings->save();
            }
            $module->invitationEmail = $settings->value;
            unset($settings);

            // init hoursInvitationLinkIsActive property
            $settings = Settings::model()->findByAttributes(array('name'=>'hoursInvitationLinkIsActive'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'hoursInvitationLinkIsActive';
                $settings->value = $module->hoursInvitationLinkIsActive;
                $settings->label = "How many hours the invitation link is active? (if <0 = forever)";
                $settings->description = "";
                $settings->save();
            }
            $module->hoursInvitationLinkIsActive = $settings->value;
            unset($settings);
            ///////////////////////////////

            // init hoursActivationLinkIsActive property
            $settings = Settings::model()->findByAttributes(array('name'=>'hoursActivationLinkIsActive'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'hoursActivationLinkIsActive';
                $settings->value = $module->hoursActivationLinkIsActive;
                $settings->label = "How many hours the activation link is active? (if <0 = forever)";
                $settings->description = "";
                $settings->save();
            }
            $module->hoursActivationLinkIsActive = $settings->value;
            unset($settings);

            // init notificationSignUpEmail property
            $settings = Settings::model()->findByAttributes(array('name'=>'notificationSignUpEmail'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'notificationSignUpEmail';
                $settings->value = $module->notificationSignUpEmail;
                $settings->label = "Activation email is sent from:";
                $settings->description = "";
                $settings->save();
            }
            $module->notificationSignUpEmail = $settings->value;
            unset($settings);
            ///////////////////////////////

            // init hoursVerificationLinkIsActive property
            $settings = Settings::model()->findByAttributes(array('name'=>'hoursVerificationLinkIsActive'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'hoursVerificationLinkIsActive';
                $settings->value = $module->hoursVerificationLinkIsActive;
                $settings->label = "How many hours the email verification link is active? (if <0 = forever)";
                $settings->description = "How many hours the email verification link is active? (when user associates a new email address to his/hers account)";
                $settings->save();
            }
            $module->hoursVerificationLinkIsActive = $settings->value;
            unset($settings);

            // init notificationVerificationEmail property
            $settings = Settings::model()->findByAttributes(array('name'=>'notificationVerificationEmail'));
            if($settings === NULL){
                // create setting and init it to it's default value
                $settings = new Settings;
                $settings->name = 'notificationVerificationEmail';
                $settings->value = $module->notificationVerificationEmail;
                $settings->label = "Verification email is sent from:";
                $settings->description = "";
                $settings->save();
                $settings->save();
            }
            $module->notificationVerificationEmail = $settings->value;
            unset($settings);
            ///////////////////////////////
        }
    }
}