<?php
/**
 * BumUserEmails class file.
 * Provides helper functions for emails table.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * BumUserEmails helper class.
 * @package		bum
 */
class BumUserEmails {
    
    /**
     * Find all emails of one user.
     * @param type $id_user
     * @return CActiveDataProvider
     */
    public static function findMyEmails($id_user){
        // everybody has the right to see their own email addresses
        // beside, users with right: emails_all_view can view other users email...
        if((Yii::app()->user->id === $id_user) || Yii::app()->user->checkAccess('emails_all_view')){
            $myEmails = Emails::findMyEmails($id_user);
        }else{
            $myEmails = false;
        }
        
        return $myEmails;
    }  
}
