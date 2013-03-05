<?php
/**
 * BumController class file.
 * BumController used to initialized the default settings of the module. 
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * 
 */
/**
 * BumActiveRecord class.
 * @package		bum
 */

abstract class BumController extends Controller{
    
    // sometines in BumModule init() code: BumSettings::checkInitSettings($this); produces an infinite loop; no ideea why
    //public function init(){
        //BumSettings::checkInitSettings(Yii::app()->getModule('bum')); // check and set the settings 
    //}
}