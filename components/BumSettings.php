<?php
/**
 * BumSettings class file.
 * Init/Load the default BUM module settings.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
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

        $command = self::$connection->createCommand("SELECT value, setting_order, version FROM settings WHERE name = '{$setting['name']}'");
        $row=$command->queryRow();
        if(!$row){
            // create setting and init it to it's default value
            $sql="INSERT INTO settings (name, value, label, description, setting_order, version) VALUES(:name, :value, :label, :description, :setting_order, :version)";

            $setting['value'] = $module->$setting['name'];
            
            $command = self::$connection->createCommand($sql);
            $command->bindParam(":name", $setting['name'], PDO::PARAM_STR);
            $command->bindParam(":value", $setting['value'], PDO::PARAM_STR);
            $command->bindParam(":label", $setting['label'], PDO::PARAM_STR);
            $command->bindParam(":description", $setting['description'], PDO::PARAM_STR);
            $command->bindParam(":setting_order",  $setting['setting_order'], PDO::PARAM_INT);
            $command->bindParam(":version",  $setting['version'], PDO::PARAM_INT);
            $command->execute();
        }else{
            $module->$setting['name'] = $row["value"];

            if ($row["version"] != $setting['version']){
                
                $sql="UPDATE settings SET label = :label, description = :description, setting_order = :setting_order, version = :version WHERE name = '{$setting['name']}'";

                //$setting['value'] = $module->$setting['name'];

                $command = self::$connection->createCommand($sql);
                //$command->bindParam(":value", $setting['value'], PDO::PARAM_STR); // because the value that is saved must be preserved
                $command->bindParam(":label", $setting['label'], PDO::PARAM_STR);
                $command->bindParam(":description", $setting['description'], PDO::PARAM_STR);
                $command->bindParam(":setting_order",  $setting['setting_order'], PDO::PARAM_INT);
                $command->bindParam(":version",  $setting['version'], PDO::PARAM_INT);
                $command->execute();
            }
        }
        
        if(isset($setting['email_content'])){
            
            $site_emails_content = $setting['email_content'];
            
            $command = self::$connection->createCommand("SELECT subject, body, version FROM site_emails_content WHERE name = '{$setting['name']}'");
            $row=$command->queryRow();
            if(!$row){
                // create site_emails_content and init it to it's default value
                $sql="INSERT INTO site_emails_content (name, subject, body, available_variables, version) VALUES(:name, :subject, :body, :available_variables, :version)";

                $command = self::$connection->createCommand($sql);
                $command->bindParam(":name", $setting['name'], PDO::PARAM_STR);
                $command->bindParam(":subject", $site_emails_content['subject'], PDO::PARAM_STR);
                $command->bindParam(":body", $site_emails_content['body'], PDO::PARAM_STR);
                $command->bindParam(":available_variables", $site_emails_content['available_variables'], PDO::PARAM_STR);
                $command->bindParam(":version",  $site_emails_content['version'], PDO::PARAM_INT);
                $command->execute();
            }else{
                if ($row["version"] != $site_emails_content['version']){

                    $sql="UPDATE site_emails_content SET subject = :subject, body = :body, available_variables = :available_variables, version = :version WHERE name = '{$setting['name']}'";

                    $command = self::$connection->createCommand($sql);
                    //$command->bindParam(":name", $setting['name'], PDO::PARAM_STR);
                    $command->bindParam(":subject", $site_emails_content['subject'], PDO::PARAM_STR);
                    $command->bindParam(":body", $site_emails_content['body'], PDO::PARAM_STR);
                    $command->bindParam(":available_variables", $site_emails_content['available_variables'], PDO::PARAM_STR);
                    $command->bindParam(":version",  $site_emails_content['version'], PDO::PARAM_INT);
                    $command->execute();
                }
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
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////

        // init enabledSignUp property
        $setting = array(
            'name' => 'enabledSignUp',
            'label' => 'SignUp is enabled?',
            'description' => 'If SignUp is disabled, no SignUps are allowed, in any case!',
            'setting_order' => 200,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////

        // init invitationBasedSignUp property
        $setting = array(
            'name' => 'invitationBasedSignUp',
            'label' => 'Only invited users are allowed to SignUp?',
            'description' => 'If SignUp is disabled, no user can SignUp, even invited ones!',
            'setting_order' => 300,
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init invitationButtonDisplay property
        $setting = array(
            'name' => 'invitationButtonDisplay',
            'label' => 'Display the invitation button to all users?',
            'description' => '',
            'setting_order' => 400,
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init invitationDefaultNumber property
        $setting = array(
            'name' => 'invitationDefaultNumber',
            'label' => 'Default number of invitations per user? <BR/> (if <0 = infinit number)',
            'description' => '',
            'setting_order' => 500,
            'version' => 2,
        );
        self::initSetting($module, $setting);

        // init sender_invitation property
        $setting = array(
            'name' => 'sender_invitation',
            'label' => 'Invitation email is sent from:',
            'description' => 'When a new user is invited to this site, the invitation email is sent from this email address..',
            'setting_order' => 600,
            'email_content'=>array(
                'subject'=>'You had been invited ;)',
                'body'=>'
$linkToSignUpInvitationPage = CHtml::link(\'here\', $this->createAbsoluteUrl(\'users/signUp\', array(\'email\'=>$model->email, \'invitationCode\'=>$model->invitation_code))); 
if ($this->module->invitationBasedSignUp):
    $body_  = "<p>To signUp, please follow the next link:<br/> {$linkToSignUpInvitationPage}<br/>";
    $body_ .= "use the following code:{$model->invitation_code} and please set this ({$model->email}) as the email address.";
else:
    $body_ = "<p>To signUp, please follow the next link:<br/> {$linkToSignUpInvitationPage}.";
endif;
if ($this->module->hoursInvitationLinkIsActive > 0): 
    $body_ .= "<A href=\'#validTime\'>*</A>!</p>";
    $body_ .= "<p><SMALL><A id=\'validTime\'>*</A>Your invitation link is valid for a period of:{$this->module->hoursInvitationLinkIsActive} hours from the time when your received this email!<SMALL></p>";
endif;
$body_ .= $model->note;
$body_ .= "</p>";

return $body_;
                    ',
                'available_variables'=>'
                    <DIV class="warning">ATTENTION! $body_ variable is parsed using eval(); function. Be very carefull who has the right to change the above php code and how you change it!</DIV>
                    
                    <DL>
                        <DT>$model</DT>
                        <DD>
                            The model "Invitations".<BR/>
                            <DL>
                                <DT>$model->invitation_code</DT>
                                <DD>The unique invitation code for this user.</DD>
                            </DL>
                        </DD>
                        
                        <DT>$this</DT>
                        <DD>The controller instance.</BR>
                            <DL>
                                <DT>$this->module</DT>
                                <DD>This module; aka BUM.</DD>
                                
                                <DT>$this->module->hoursInvitationLinkIsActive</DT>
                                <DD>How many hours the invitation link is active/available? (if <0 = forever)</DD>
                            </DL>
                        </DD>
                    </DL>
                    ',
                'version'=>1,
            ),
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init hoursInvitationLinkIsActive property
        $setting = array(
            'name' => 'hoursInvitationLinkIsActive',
            'label' => 'How many hours the invitation link is active? <BR/> (if <0 = forever)',
            'description' => '',
            'setting_order' => 700,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////

        // init sender_signUp property
        $setting = array(
            'name' => 'sender_signUp',
            'label' => 'Activation email is sent from:',
            'description' => 'When a new user register to this site, an activation email is sent, in order to verify its email address authenticity.',
            'setting_order' => 800,
            'email_content'=>array(
                'subject'=>'Activation email.',
                'body'=> '
$linkToActivationPage = CHtml::link(\'here\', $this->createAbsoluteUrl(\'users/activate\', array(\'acKey\'=>$modelUsersData->activation_code)));
$body_ = "<p>In order to activate your account please press {$linkToActivationPage}";
if ($this->module->hoursActivationLinkIsActive > 0):    
    $body_ .= "<A href=\'#validTime\'>*</A>!<p>";
    $body_ .= "<p><SMALL><A id=\'validTime\'>*</A>Your activation link is valid for a period of: {$this->module->hoursActivationLinkIsActive} hours from the time when your account was created!<SMALL></p>";
endif;

return $body_;
                    ',
                'available_variables'=>'
                    <DIV class="warning">ATTENTION! $body_ variable is parsed using eval(); function. Be very carefull who has the right to change the above php code and how you change it!</DIV>
                    
                    <DL>
                        <DT>$modelUsersData</DT>
                        <DD>
                            The model "UsersData".<BR/>
                            <DL>
                                <DT>$modelUsersData->activation_code</DT>
                                <DD>The unique activation code of this user.</DD>
                            </DL>
                        </DD>
                        
                        <DT>$this</DT>
                        <DD>The controller instance.</BR>
                            <DL>
                                <DT>$this->module</DT>
                                <DD>This module; aka BUM.</DD>
                                
                                <DT>$this->module->hoursActivationLinkIsActive</DT>
                                <DD>How many hours the activation link is active/available? (if <0 = forever)</DD>
                            </DL>
                        </DD>
                    </DL>
                    ',
                'version'=>1,
            ),
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init hoursActivationLinkIsActive property
        $setting = array(
            'name' => 'hoursActivationLinkIsActive',
            'label' => 'How many hours the activation link is active? <BR/> (if <0 = forever)',
            'description' => '',
            'setting_order' => 900,
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init sender_signUp property
        $setting = array(
            'name' => 'sender_signUp_thankYou',
            'label' => 'Sign Up thank you email responder:',
            'description' => 'When a new user register to this site, and activate his/hers account, a thank you email is sent, informing about the site offerings.',
            'setting_order' => 933,
            'email_content'=>array(
                'subject'=>'Thank you for joining our greate site!',
                'body'=> '
$linkToLogInPage = CHtml::link($this->createAbsoluteUrl(\'/bum/users/login\'), $this->createAbsoluteUrl(\'/bum/users/login\'));
$body_ = "<p>Dear $modelUsers->user_name,</p> <p>Welcome to our community, we hope you enjoy it as much as us ;)</p> <p>Your user name is: {$modelUsers->user_name}<br/>Log in page is: {$linkToLogInPage}</p> <p>Yours faithfully,<br/>site team.</p>";

return $body_;
                    ',
                'available_variables'=>'
                    <DIV class="warning">ATTENTION! $body_ variable is parsed using eval(); function. Be very carefull who has the right to change the above php code and how you change it!</DIV>
                    
                    <DL>
                        <DT>$modelUsers</DT>
                        <DD>
                            The model "Users".<BR/>
                            <DL>
                                <DT>$modelUsers->user_name</DT>
                                <DD>User name of current user.</DD>
                            </DL>
                        </DD>
                        
                        <DT>$this</DT>
                        <DD>The controller instance.</BR>
                            <DL>
                                <DT>$this->module</DT>
                                <DD>This module; aka BUM.</DD>
                            </DL>
                        </DD>
                    </DL>
                    ',
                'version'=>6,
            ),
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init enabledSignUpThankYou property
        $setting = array(
            'name' => 'enabledSignUpThankYou',
            'label' => 'Enable SignUp "thank you" email?',
            'description' => '',
            'setting_order' => 943,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        
        ///////////////////////////////

        // init sender_registerNewEmail property
        $setting = array(
            'name' => 'sender_registerNewEmail',
            'label' => 'Verification email is sent from:',
            'description' => 'When a user register a new email to its account, a verification email is sent to that email in order to confirm the email address authenticity.',
            'setting_order' => 1000,
            'email_content'=>array(
                'subject'=>'Email Verification',
                'body'=>'
$linkToEmailVerificationPage = CHtml::link(\'here\', $this->createAbsoluteUrl(\'emails/verify\', array(\'ckKey\'=>$modelEmails->verification_code)));
$body_ = "<p>To verify your email address, please follow this link {$linkToEmailVerificationPage}.";
if ($this->module->hoursVerificationLinkIsActive > 0):
    $body_ .= "<A href=\'#validTime\'>*</A>!<p>";
    $body_ .= "<p><SMALL><A id=\'validTime\'>*</A>Your verification link is valid for a period of: {$this->module->hoursVerificationLinkIsActive} hours from the time when your submitted your email!<SMALL></p>";
endif;

return $body_;
                        ',
                'available_variables'=>'
                    <DIV class="warning">ATTENTION! $body_ variable is parsed using eval(); function. Be very carefull who has the right to change the above php code and how you change it!</DIV>
                    
                    <DL>
                        <DT>$modelEmails</DT>
                        <DD>
                            The model "Emails".<BR/>
                            <DL>
                                <DT>$modelEmails->verification_code</DT>
                                <DD>The verification code needed to verify user\'s email address.</DD>
                            </DL>
                        </DD>
                        
                        <DT>$this</DT>
                        <DD>The controller instance.</BR>
                            <DL>
                                <DT>$this->module</DT>
                                <DD>This module; aka BUM.</DD>
                                
                                <DT>$this->module->hoursVerificationLinkIsActive</DT>
                                <DD>How many hours the email verification link is active? (if <0 = forever)</DD>
                            </DL>
                        </DD>
                    </DL>
                    ',
                'version'=>1,
            ),
            'version' => 2,
        );
        self::initSetting($module, $setting);
        
        // init hoursVerificationLinkIsActive property
        $setting = array(
            'name' => 'hoursVerificationLinkIsActive',
            'label' => 'How many hours the email verification link is active? (if <0 = forever)',
            'description' => 'How many hours the email verification link is active? (when user associates a new email address to his/hers account)',
            'setting_order' => 1100,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////
        //
        // init sender_passwordRecovery property
        $setting = array(
            'name' => 'sender_passwordRecovery',
            'label' => 'Password recovery email is sent from:',
            'description' => 'When a user forgot his/hers password and ask for a new password.',
            'setting_order' => 1200,
            'email_content'=>array(
                'subject'=>'You requested a new password‏',
                'body'=>'
$linkToPasswordRecoveryAskCodePage = CHtml::link(\'here\', $this->createAbsoluteUrl(\'users/passwordRecoveryAskCode\', array(\'lc\'=>$modelPasswordRecovery->long_code, \'em\'=> md5($modelPasswordRecovery->email), \'code\'=>$modelPasswordRecovery->code)));
$body_  = "<p>To reset your password, please follow this link <b>{$linkToPasswordRecoveryAskCodePage}</b></p>";
$body_ .= "<p>and insert the following code <b>{$modelPasswordRecovery->code}</b></p>";
$body_ .= "<A href=\'#validTime\'>*</A>!<p>";
$body_ .= "<p><SMALL><A id=\'validTime\'>*</A>Your link is valid for a period of:{$this->module->hoursPasswordRecoveryLinkIsActive} hours from the time when your placed the request!<SMALL></p>";

return $body_; 
                    ',
                'available_variables'=>'
                    <DIV class="warning">ATTENTION! $body_ variable is parsed using eval(); function. Be very carefull who has the right to change the above php code and how you change it!</DIV>
                    
                    <DL>
                        <DT>$modelPasswordRecovery</DT>
                        <DD>
                            The model "PasswordRecovery".<BR/>
                            <DL>
                                <DT>$modelPasswordRecovery->long_code</DT>
                                <DD>The unique password recovery code (the long code used in the link to recovey page).</DD>
                                
                                <DT>$modelPasswordRecovery->code</DT>
                                <DD>The unique password recovery code (should be inserted by the user).</DD>
                            </DL>
                        </DD>
                        
                        <DT>$this</DT>
                        <DD>The controller instance.</BR>
                            <DL>
                                <DT>$this->module</DT>
                                <DD>This module; aka BUM.</DD>
                                
                                <DT>$this->module->hoursPasswordRecoveryLinkIsActive</DT>
                                <DD>How many hours the password recoverty link is active/available?</DD>
                            </DL>
                        </DD>
                    </DL>
                    ',
                'version'=>1,
            ),
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init hoursPasswordRecoveryLinkIsActive property
        $setting = array(
            'name' => 'hoursPasswordRecoveryLinkIsActive',
            'label' => 'How many hours the password recovery request is active?',
            'description' => '',
            'setting_order' => 1300,
            'version' => 1,
        );
        self::initSetting($module, $setting);

        // init trackPasswordRecoveryRequests property
        $setting = array(
            'name' => 'trackPasswordRecoveryRequests',
            'label' => 'Track password recovery requests?',
            'description' => 'If it\'s true, than if a password recovery request expire, it is not deleted, but it\'s property "expired" is set to true. So in the database remain all password requests that have been made.',
            'setting_order' => 1400,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////
        //
        // init facebook appId
        $setting = array(
            'name' => 'fb_appId',
            'label' => 'Facebook App ID',
            'description' => '',
            'setting_order' => 1400,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        
        // init facebook secret
        $setting = array(
            'name' => 'fb_secret',
            'label' => 'Facebook secret',
            'description' => '',
            'setting_order' => 1400,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        //
        // init twitter consumerKey
        $setting = array(
            'name' => 'twitter_key',
            'label' => 'Twitter Consumer key',
            'description' => '',
            'setting_order' => 1400,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        
        // init facebook secret
        $setting = array(
            'name' => 'twitter_secret',
            'label' => 'Twitter customer secret',
            'description' => '',
            'setting_order' => 1400,
            'version' => 1,
        );
        self::initSetting($module, $setting);
        ///////////////////////////////
    }
}