<?php
/**
 * EmailsController class file.
 * Controller class file for table emails.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * EmailsController class.
 * @package		bum
 */
class EmailsController extends BumController {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/bum';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'ajaxOnly + delete, viewMyEmails, hasUnverifiedEmails, makePrimary', // we only allow deletion via POST request
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
				'actions'=>array('create', 'resendVerificationLink', 'verify', 'delete', 'viewMyEmails', 'hasUnverifiedEmails', 'makePrimary'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * 
     * @param type $id_user
     * @param type $JsRender This parameter specifies if the returned code should be postprocessed or not...
     */
	public function actionCreate($id_user, $processOutput = false) {
        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
        }
        if((Yii::app()->user->id === $id_user) || Yii::app()->user->checkAccess('emails_create')){
            $model=new Emails;
            $modelUsers = Users::model()->findByPk($id_user);

            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST['Emails']))
            {
                $model->attributes=$_POST['Emails'];
                
                // for now check only if this user has added this address already. Other users can have this address added as well..
                $exists = Emails::model()->findAllByAttributes(array('id_user'=>$model->id_user, 'name'=>$model->name));
                
                // if the email is already in the database, do nothing, else do the followings:
                if (count($exists) == 0){
                    // $model->id_user=$modelUsers->id; // already set in the form, using hidden button
                    $model->verification_code = sha1(mt_rand(1, 99999).time().$model->name);
                    $model->visible = true;

                    if($model->save()){
                        $message = $this->sendVerificationEmail($model);

                        if(Yii::app()->mail->send($message)){
                            // refresh emails
                            // clear add new email form
                            return;
                        }else{
                            // something went wrong...
                        }
                    }
                }else{
                    unset($exists);
                    $model->unsetAttributes();
                }
            }
            
            $this->renderPartial('_form', array(
                    'model'=>$model,
                    'modelUsers'=>$modelUsers
                ), false, $processOutput);
        }
	}
    
    /**
     * Print user emails.
     * @param type $id_user
     */
    public function actionViewMyEmails($id_user, $processOutput = false){
        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
        }
        
        $this->renderPartial('_editMyEmails', array(
                   'myEmails'=>  BumUserEmails::findMyEmails($id_user),
               ), false, $processOutput);
    }
    
    /**
     * 
     * @param type $id_user
     */
    public function actionHasUnverifiedEmails($id_user){
        // only AJAX requests
        echo json_encode(Emails::hasUnverifiedEmails($id_user));
    }
    
    /**
     * Resend the verification email...
     * @param type $id
     */
    public function actionResendVerificationLink($id){
        $model = $this->loadModel($id);
        
        if((Yii::app()->user->id === $model->id_user) || Yii::app()->user->checkAccess('emails_verificationLink_resend')){
            $message = $this->sendVerificationEmail($model);
            if(Yii::app()->mail->send($message)){
                echo 'sent';
            }else{
                // something went wrong...
                echo 'not sent';
            }
        }
    }
    
    /**
     * Confirm email verification.
     * @param type $ckKey
     */
    public function actionVerify($ckKey){
        $model = Emails::model()->findByAttributes(array('verification_code' => $ckKey));
        
        if ($model === NULL) {
            Yii::app()->user->setFlash('notice', "Nothing to verify!");
            $this->redirect(array('users/update', 'id'=>Yii::app()->user->id));
        }else{
            // prevent email activation by other persons...
            if (Yii::app()->user->id === $model->id_user) {
                // the check should be made using chron
                // check if the link is still active
                //$secFromEamilCreation = (time() - strtotime($model->date_of_creation));
                //$hoursFromEmailCreation = round($secFromEamilCreation/(60*60));
                
                // if the link is not active anymore
                //if ($hoursFromEmailCreation >= $this->module->hoursVerificationLinkIsActive && $this->module->hoursVerificationLinkIsActive > 0) {
                    // delete email;
                    // $model->delete();
                    // the email are deleted from chronos
                    
                //    Yii::app()->user->setFlash('notice', "Nothing to verify!");
                //    $this->redirect(array('users/update', 'id'=>Yii::app()->user->id));
                //}else{
                    // the emails has been verified corectly 
                    $model->verified = true;
                    $model->save(false);
                    
                    Yii::app()->user->setFlash('success', "Email address had been succesfully verified.");

                    $this->redirect(array('users/update', 'id'=>Yii::app()->user->id));
                //}
            }else{
                // redirect the "other" user
                $this->redirect(array('users/update', 'id'=>Yii::app()->user->id));
            }
        }
    }

    /**
     * Send the verification email. 
     * Uses Yii-Mail extension.
     * 
     * @param type $Emails
     * @return \YiiMailMessage
     */
    public function sendVerificationEmail($model){
        $message = new YiiMailMessage;
        
        $message->view = 'emailVerification';
        $message->setBody(array('modelEmails'=>$model), 'text/html');
        $message->subject = 'Email Verification';
        $message->addTo($model->name);
        $message->from = $this->module->notificationVerificationEmail;
        
        return $message;
    }
    
    /**
     * 
     * @param type $id
     * @param boolean $processOutput
     */
    public function actionDelete($id, $processOutput = false){
        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
        }
        $model = $this->loadModel($id);
        
        $id_user = $model->id_user;
        if((Yii::app()->user->id === $model->id_user) || Yii::app()->user->checkAccess('emails_delete')){
            $model->delete();
        }
        $this->renderPartial('_editMyEmails', array(
                   'myEmails'=>  BumUserEmails::findMyEmails($id_user),
               ), false, $processOutput);
    }
    
    /**
     * Change the primary email to be another email address.
     * 
     * 1 in table users email must be unique; test if this email is already used
     * 2 if it is not used in table users, start a transaction
     * 3 then update table users, set email to this email, 
     * 3 make this email to be unseen in the emails table
     * 4 insert or make visibe previous primary email in emails table (also mark it as confirmed)
     * 
     * @param type $id
     */
    public function actionMakePrimary($id){
        $model = $this->loadModel($id);
        $modelUsers = Users::model()->findByAttributes(array('email'=>$model->name));
        $response = array();
        
        if ($modelUsers !== NULL){
            if ($modelUsers->id != $model->id_user) {
                $response["changed"] = false;
                $response["message"] = "It seems that this email address is already used by another user!";
                $response["name"] = "";
            }else{
                $response["changed"] = false;
                $response["message"] = "It seems that this is already your primary email address!";
                $response["name"] = "";
                
                $model->visible = false;
                $model->save();
            }
        }else{
            $connection=Yii::app()->db;
            $transaction=$connection->beginTransaction();
            try {
                $modelUsers = Users::model()->findByPk($model->id_user);
                
                $oldPrimaryEmail = $modelUsers->email;
                $modelUsers->email = $model->name;
                $model->visible = false;
                
                $modelUsers->save();
                $model->save();
                
                $newEmail = Emails::model()->findByAttributes(array('id_user'=>$model->id_user, 'name'=>$oldPrimaryEmail));
                if($newEmail !== NULL){
                    //$newEmail = array_shift($newEmail); // only one record can be found; findAllByAttributes reutrns an array;
                    $newEmail->visible = true;
                    $newEmail->save();
                }else{
                    $newEmail = new Emails;
                    
                    $newEmail->id_user = $model->id_user;
                    $newEmail->name = $oldPrimaryEmail;
                    $newEmail->visible = true;
                    $newEmail->verified = true; // set it as verified, because it already was primaryEmail, so it already was verified                
                    $newEmail->save();
                }   
                
                
                $response["changed"] = true;
                $response["message"] = "Primary email was succesfully changed!";
                $response["name"] = $model->name;
                
                // commit transaction
                $transaction->commit();
            } catch(Exception $e) {
                $transaction->rollBack();
                
                $response["changed"] = false;
                $response["message"] = "Something went wrong!";
                $response["name"] = "";
            }                
            
        }
        
        echo json_encode($response);
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Emails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='emails-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
