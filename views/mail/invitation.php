<?php
/**
 * Email invitation view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user invites another person to this site, this email is sent to the respective.
 */

/* @var $model Invitations */

$linkToSignUpInvitationPage = CHtml::link('here', $this->createAbsoluteUrl('users/signUp', array('email'=>$model->email, 'invitationCode'=>$model->invitation_code)));

if (Yii::app()->getModule('bum')->invitationBasedSignUp):
    
    ?><p>To signUp, please follow the next link:<br/> <?php echo $linkToSignUpInvitationPage; ?><br/> <?php
    ?>use the following code: <?php echo $model->invitation_code; ?> and please set this (<?php echo $model->email; ?>) as the email address.<?php
else:
    ?><p>To signUp, please follow the next link:<br/> <?php echo $linkToSignUpInvitationPage; ?>. <?php
endif;

if ($this->module->hoursInvitationLinkIsActive > 0):
    ?> <A href='#validTime'>*</A>!</p>
    <p><SMALL><A id='validTime'>*</A>Your invitation link is valid for a period of: <?php echo $this->module->hoursInvitationLinkIsActive; ?> hours from the date when your received this email!<SMALL></p><?php
endif;

?><p><?php echo $model->note; ?>.</p> <?php
