<?php
/**
 * BumActiveRecord class file.
 * BumActiveRecord extends CActiveRecord in order to prevent savind and deleting of data it the module is in demo mode.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * BumActiveRecord class.
 * @package		bum
 */

abstract class BumActiveRecord extends CActiveRecord {
    
	public function beforeDelete() {
        if(Yii::app()->getModule('bum')->demoMode){
            Yii::app()->user->setFlash('notice', "Demo mode is active! No changes allowed!");
            return FALSE;
        }else{
            return parent::beforeDelete();
        }
	}
    
	public function beforeSave() {
        if(Yii::app()->getModule('bum')->demoMode){
            Yii::app()->user->setFlash('notice', "Demo mode is active! No changes allowed!");
            return FALSE;
        }else{
            return parent::beforeSave();
        }
	}
    
}