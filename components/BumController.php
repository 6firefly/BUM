<?php
/**
 * BumController class file.
 * BumController used to initialized the default settings of the module. 
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
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
    
    public function init(){
        if(!Yii::app()->user->isGuest && !Yii::app()->getModule('bum')->logInIfNotVerified){
            if (Yii::app()->user->status == Users::STATUS_ONLY_FACEBOOK) {
                Yii::app()->user->setFlash('notice', "Please update your profile! " . CHtml::link('Update Profile Information!', array('users/socialUpdate', 'id'=>Yii::app()->user->id)) );                        
            }elseif (!Yii::app()->user->active) {
                Yii::app()->user->setFlash('notice', "Please activate your account! " . CHtml::link('Resend confirmation email!', array('users/resendSignUpConfirmationEmail')) );                        
            }
        }
    }
}