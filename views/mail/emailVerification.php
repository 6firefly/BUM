<?php
/**
 * Email verification view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user register a new email address, this verification email is sent in order to confirm newly added email.
 */

/* @var $modelEmails modelEmails */

$linkToEmailVerificationPage = CHtml::link('here', $this->createAbsoluteUrl('emails/verify', array('ckKey'=>$modelEmails->verification_code)));
?>
<p>To verify your email address, please follow this link <?php echo $linkToEmailVerificationPage;

if ($this->module->hoursVerificationLinkIsActive > 0):
    ?> <A href='#validTime'>*</A>!<p>
    <p><SMALL><A id='validTime'>*</A>Your verification link is valid for a period of: <?php echo $this->module->hoursVerificationLinkIsActive; ?> hours from the date when your submitted your email!<SMALL></p><?php
endif;
            