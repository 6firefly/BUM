<?php
/**
 * View my email view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $data Emails */
?><div><?php 

    echo CHtml::encode($data->name); 
    if(!$data->verified) : 
        ?> <SMALL><I>not verified</I></SMALL><?php
    endif;
    
?></div><?php
