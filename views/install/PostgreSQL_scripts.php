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
	'Install',
);

$this->menu=array(    
	array('label'=>'Install with MySQL', 'url'=>array('install/MySQL'), 'visible'=>(Yii::app()->getModule("bum")->install)),
	array('label'=>'Install with PostgreSQL', 'url'=>array('install/PostgreSQL'), 'visible'=>(Yii::app()->getModule("bum")->install)),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->getModule("bum")->install)), // separator
    
	array('label'=>'Usefull things (How to?)', 'url'=>array('install/howTo'), 'visible'=>(Yii::app()->getModule("bum")->install)),
);

$file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install.v2.04.postgre.sql');

?>
<PRE><?php
    echo $file;
?></PRE><?php

