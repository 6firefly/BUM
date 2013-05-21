<?php
/**
 * Install controller main view file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License
 *  
 */

/* @var $this InstallController */

$this->breadcrumbs=array(
	'Install' => array('install/'),
	'How To',
);

$this->menu=array(    
	array('label'=>'Install with MySQL', 'url'=>array('install/MySQL'), 'visible'=>($this->module->install)),
	array('label'=>'Install with PostgreSQL', 'url'=>array('install/PostgreSQL'), 'visible'=>($this->module->install)),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>($this->module->install)), // separator
    
	array('label'=>'Usefull things (How to?)', 'url'=>array('install/howTo'), 'visible'=>($this->module->install)),
);

?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<UL>
    <LI>
        How to check if a user is active:
        <DIV class="box">
            Yii::app()->user->active; //type: boolean;
        </DIV>
    </LI>
    <LI>
        How to find a user primary email:
        <DIV class="box">
            Yii::app()->user->primaryEmail;
        </DIV>
    </LI>
    <LI>
        How to find a user status code:
        <DIV class="box">
            Yii::app()->user->status;
        </DIV>
    </LI>
    <LI>
        How to find a user status text:
        <DIV class="box">
            Yii::app()->user->statusText;
        </DIV>
    </LI>
    
    <LI>
        How customize automatically sent email by BUM module:<br/>
        check path: bum/views/mail/

        <dl>
            <dt>emailVerification.php</dt>
                <dd>When a user register a new email address, this verification email is sent in order to confirm newly added email.</dd>
            <dt>invitation.php</dt>
                <dd>When a user invites another person to this site, this email is sent to the respective.</dd>
            <dt>passwordRecovery.php</dt>
                <dd>When a user request for password recovery (password reset) this email is sent to the user's email.</dd>
            <dt>signUpEmail.php</dt>
                <dd>When a user register, this confirmation/activation email is sent to that user.</dd>
        </dl>
        
    </LI>
            
</UL>