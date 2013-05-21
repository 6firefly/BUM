<?php
/**
 * Password recovery email view file; partial view.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user request for password recovery (password reset) this email is sent to the user's email.
 */

/* @var $modelPasswordRecovery $modelPasswordRecovery */

$linkToPasswordRecoveryAskCodePage = CHtml::link('here', $this->createAbsoluteUrl('users/passwordRecoveryAskCode', array('lc'=>$modelPasswordRecovery->long_code, 'em'=> md5($modelPasswordRecovery->email), 'code'=>$modelPasswordRecovery->code)));
?>
<p>To reset your password, please follow this link <b><?php echo $linkToPasswordRecoveryAskCodePage; ?></b></p>
<p>and insert the following code <b><?php echo $modelPasswordRecovery->code; ?></b></p><?php

?> <A href='#validTime'>*</A>!<p>
<p><SMALL><A id='validTime'>*</A>Your link is valid for a period of: <?php echo $this->module->hoursPasswordRecoveryLinkIsActive; ?> hours from the date when your placed the request!<SMALL></p><?php
            