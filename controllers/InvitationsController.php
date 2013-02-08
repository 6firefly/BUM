<?php
/**
 * InvitationsController class file.
 * Controller class file for table invitations.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * InvitationsController class.
 * @package		bum
 */

class InvitationsController extends BumController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/bum';

    /**
     * @var type Users; keeps the user that makes the invitation
     */
    private $_user = null;
    
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
            'userContext + AJAXCreate', // check to ensure valid user context
            'ajaxOnly + AJAXCreate, AJAXView', // allow only AJAX request
		);
	}

    /**
     * Protected method to load the associated Users model class
     * @param type $id_user
     * @throws CHttpException
     */
    protected function loadUser($id_user) {
        //if the user property is null, create it based on input id
        if($this->_user===null) {
            $this->_user = Users::model()->findbyPk($id_user);
            if($this->_user===null) {
                throw new CHttpException(404,'There is no user to place the invitation.');
            }
        }
        return $this->_user;
    }
    
    /**
     * Find the user that placed the request.
     * @param type $filterChain
     */
    public function filterUserContext($filterChain)
    {
        //set the user identifier based on either the GET or POST input
        //request variables
        $id_user = null;
        if(isset($_GET['id_user'])){
            $id_user = $_GET['id_user'];
        }elseif(isset($_POST['Invitations']['id_user'])){
            $id_user = $_POST['Invitations']['id_user'];
        }
        
        $this->loadUser($id_user);
        //complete the running of other filters and execute the requested action
        $filterChain->run();
    }
    
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'AJAXCreate' and 'AJAXView' actions
				'actions'=>array('AJAXCreate', 'AJAXView'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
    /**
	 * Creates a new model.
	 */
	public function actionAJAXCreate($processOutput = false){
        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            // some jquery.yii scripts are still included several times; no observable problem, but still...
        }
        $model=new Invitations;
        $model->scenario = 'create';
        $model->id_user = $this->_user->id;
        
		$UsersData=UsersData::model()->findByPk($model->id_user);
        
        if($UsersData->invitations_left < 0 || $UsersData->invitations_left > 0){
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST['Invitations']))
            {
                $model->attributes=$_POST['Invitations'];
                $model->invitation_code = rand(1000, 9999);

        
                if($model->save()){
                    
                    $message = $this->sendInvitationEmail($model);
                    if(Yii::app()->mail->send($message)){
                        if($UsersData->invitations_left>0){
                            $UsersData->invitations_left--;
                            $UsersData->save();
                        }

                        echo json_encode(array('message'=>'Invitation sent!', 'status'=>'ok'));
                    }else{
                        // something went wrong...
                        echo json_encode(array('message'=>'Invitation could not be sent!', 'status'=>'not ok'));
                    }
                    
                }else{
                    $error = CActiveForm::validate($model);
                    if($error!='[]')
                        echo $error;
                }
            }else{
                $this->renderPartial('_AjaxForm', array('model'=>$model), false, $processOutput);
            }
        }else{
            Yii::app()->user->setFlash('notice', "No invitations left!");
            $this->renderPartial('_noInvitations', array('model'=>$model), false, $processOutput);
        }
	}

    /**
     * Send the invitation email. 
     * Uses Yii-Mail extension.
     * 
     * @param type $Emails
     * @return \YiiMailMessage
     */
    public function sendInvitationEmail($model){
        $message = new YiiMailMessage;
        
        $message->view = 'invitation';
        $message->setBody(array('model'=>$model), 'text/html');
        $message->subject = 'You had been invited ;)';
        $message->addTo($model->email);
        $message->from = $this->module->invitationEmail;
        
        return $message;
    }
    
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAJAXView($id_user, $processOutput = false){
        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            // some jquery.yii scripts are still included several times; no observable problem, but still...
        }
        $dataProvider=new CActiveDataProvider('Invitations', array(
            'criteria'=>array(
                'condition'=>"id_user={$id_user}",
                'order'=>'date_of_invitation_send DESC',
            ),
            'pagination'=>array(
                'pageSize'=>5,
            ),
        ));
        
        $this->renderPartial('_AJAXView', array('dataProvider'=>$dataProvider), false, $processOutput);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Invitations::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='invitations-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
