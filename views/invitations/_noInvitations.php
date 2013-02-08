<?php
/**
 * Print a flash message; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This form file is used to print the "No invitations left" flash message...
 */

/* @var $this InvitationController */
/* @var $model Invitations */

foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
} 