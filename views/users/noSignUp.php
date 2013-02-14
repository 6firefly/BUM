<?php
/**
 * No Sign Up form.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * Display messages if user are not allowed to sign up.
 */

/* @var $this UsersController */
/* @var $model Users */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'noSignUp',
);
?>

<h1>Sign Up</h1><?php

foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
} 