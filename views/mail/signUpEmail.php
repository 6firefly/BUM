<?php
/**
 * Sign up confirmation email view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user register, this confirmation/activation email is sent to that user.
 */

/* @var $modelUsersData modelUsersData */

$linkToActivationPage = CHtml::link('here', $this->createAbsoluteUrl('users/activate', array('acKey'=>$modelUsersData->activation_code)));

?><p>In order to activate your account please press <?php echo $linkToActivationPage;



if ($this->module->hoursActivationLinkIsActive > 0):
    ?> <A href='#validTime'>*</A>!<p>
    <p><SMALL><A id='validTime'>*</A>Your activation link is valid for a period of: <?php echo $this->module->hoursActivationLinkIsActive; ?> hours from the date when your account was created!<SMALL></p><?php
endif;
            