<?php
/**
 * CronController class file.
 * Controller class file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * CronController class.
 * Delete unactivated/unactive users, and unconfirmed emails.
 * 
 * Users that are older than hoursActivationLinkIsActive and are seted as unactive will also be deleted.
 * 
 * The CronController should be added to the cron event.
 * @package		bum
 */
class CronController extends BumController {

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform certain actions; some actions may require even more rights
				'actions'=>array('deleteUnactivateUsers', 'deleteUnverifiedEmails', 'deleteUnusedInvitationEmails'),
				'ips'=>array('127.0.0.1','::1'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
	public function actionDeleteUnactivateUsers() {
        if(Yii::app()->getModule('bum')->hoursActivationLinkIsActive > 0){
            try{
                $unactivatedUsers = Users::model()->findAllByAttributes(
                            array('active'=>0), 
                            $condition = '((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_of_creation))/3600)  > :hoursActivationLinkIsActive',
                            $params = array(':hoursActivationLinkIsActive' => Yii::app()->getModule('bum')->hoursActivationLinkIsActive)
                        );
            }  catch (Exception $e){
                $unactivatedUsers = Users::model()->findAllByAttributes(
                            array('active'=>0), 
                            $condition = '((extract (epoch from (NOW()::timestamp - date_of_creation::timestamp)))/3600)::integer  > :hoursActivationLinkIsActive',
                            $params = array(':hoursActivationLinkIsActive' => Yii::app()->getModule('bum')->hoursActivationLinkIsActive)
                        );
            }
            foreach ($unactivatedUsers AS $unactivatedUser){
                $unactivatedUser->delete();
            }
            
        }
	}

	public function actionDeleteUnverifiedEmails() {
        if(Yii::app()->getModule('bum')->hoursVerificationLinkIsActive > 0){
            try{
                $unverifiedEmails = Users::model()->findAllByAttributes(
                            array('verified'=>0), 
                            $condition = '((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_of_creation))/3600)  > :hoursVerificationLinkIsActive',
                            $params = array(':hoursVerificationLinkIsActive' => Yii::app()->getModule('bum')->hoursVerificationLinkIsActive)
                        );
            }  catch (Exception $e){
                $unverifiedEmails = Emails::model()->findAllByAttributes(
                            array('verified'=>0), 
                            $condition = '((extract (epoch from (NOW()::timestamp - date_of_creation::timestamp)))/3600)::integer  > :hoursVerificationLinkIsActive',
                            $params = array(':hoursVerificationLinkIsActive' => Yii::app()->getModule('bum')->hoursVerificationLinkIsActive)
                        );
            }
            foreach ($unverifiedEmails AS $unverifiedEmail){
                $unverifiedEmail->delete();
            }
            
        }
	}

	public function actionDeleteUnusedInvitationEmails() {
        if(Yii::app()->getModule('bum')->hoursInvitationLinkIsActive > 0){
            try{
                $unusedEmails = Invitations::model()->findAllByAttributes(
                            array('date_of_invitation_accepted'=>NULL), 
                            $condition = '((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_of_invitation_send))/3600)  > :hoursInvitationLinkIsActive',
                            $params = array(':hoursInvitationLinkIsActive' => Yii::app()->getModule('bum')->hoursInvitationLinkIsActive)
                        );
            }  catch (Exception $e){
                $unusedEmails = Invitations::model()->findAllByAttributes(
                            array('date_of_invitation_accepted'=>NULL), 
                            $condition = '((extract (epoch from (NOW()::timestamp - date_of_invitation_send::timestamp)))/3600)::integer  > :hoursInvitationLinkIsActive',
                            $params = array(':hoursInvitationLinkIsActive' => Yii::app()->getModule('bum')->hoursInvitationLinkIsActive)
                        );
            }
            foreach ($unusedEmails AS $unusedEmail){
                $unusedEmail->delete();
            }
            
        }
	}

}


