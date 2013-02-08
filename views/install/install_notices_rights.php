<?php
/**
 * Install default rights, view file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License
 *  
 */

/* @var $rights array() => formated for CTreeView widget */

foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
} 

$this->widget('CTreeView',array('data'=>$rights,'animated'=>'slow','collapsed'=>false));

