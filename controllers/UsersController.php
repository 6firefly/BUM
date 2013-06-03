<?php
/**
 * UsersController class file.
 * Controller class file for table users.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * UsersController class.
 * @package		bum
 */

class UsersController extends BumController
{
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
			'postOnly + delete', // we only allow deletion via POST request
            'statusCheck', // check the status before any operation
		);
	}
    
    /**
     * Check if the status permits the access of the account; positive status values means that the user can access his/hers account.
     * @param type $filterChain
     */
    public function filterStatusCheck($filterChain)
    {
        if(Yii::app()->user->id!==null){ // if there is an logged user, check his/hers status..

            if(Yii::app()->user->status<0) {
                switch (Yii::app()->user->status){
                    case Users::STATUS_BLOCKED:
                    case Users::STATUS_BANNED:
                            throw new CHttpException(401,'You are not authorize to access this page! Your account is ' . Yii::app()->user->getStatusText() . '.');

                        break;
                    default :
                        throw new CHttpException(401,'You are not authorize to access this page.');
                }
            }
        }
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
			array('allow',  // 
				'actions'=>array('logIn', 'viewProfile', 'signUp', 'resendSignUpConfirmationEmail', 'passwordRecoveryWhatUser', 'passwordRecoveryAskCode', 'activate', 'captcha'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform certain actions; some actions may require even more rights
				'actions'=>array('viewMyPrivateProfile', 'viewProfile', 'update', 'viewAllUsers','admin','delete','create'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
     * Init some external actions.
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
    
	/**
	 * Displays the login page
	 */
	public function actionLogIn()
	{
		$model = new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    /**
	 * Display all peaces of information for my private profile.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewMyPrivateProfile($id)
	{
		// every user has the right to view its profile...
        // beside, the users with the right "users_all_privateProfile_view" also has the right to view other users profile...
        if( (Yii::app()->user->id === $id) || Yii::app()->user->checkAccess('users_all_privateProfile_view')){
            $model=$this->loadModel($id);

            $this->render('viewMyPrivateProfile',array(
                'model'=>$model,
                'myEmails'=> BumUserEmails::findMyEmails($model->id),                 
            ));
        }else{
            throw new CHttpException(403, "You are not authorized to perform this action.");            
        }
	}

	/**
	 * Display public peaces of information for my profile.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewProfile($id)
	{
        $model=$this->loadModel($id);

        $this->render('viewProfile',array(
            'model'=>$model,
        ));
	}
    
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        if( ( Yii::app()->user->checkAccess('users_create')) ){
            $model=new Users;
            $model->email = ""; // because default is "noEmail"
            $modelUsersData=new UsersData;
            $modelEmails = new Emails;
            
            $model->scenario = 'create';

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Users']))
            {
                $model->attributes=$_POST['Users'];

                // if isset($_POST['Users']) is true, this should be also true...
                if(isset($_POST['UsersData'])){
                    $modelUsersData->attributes=$_POST['UsersData'];            
                    $modelUsersData->invitations_left=Yii::app()->getModule('bum')->invitationDefaultNumber;            
                    
                    $modelUsersData->id = 0; // to pass the validation; too see if there are other problems..
                    if($modelUsersData->validate() && $model->save()){
                        
                        // automatically insert this email into emails table!
                        $modelEmails->id_user = $model->id;
                        $modelEmails->name = $model->email;
                        $modelEmails->save();
                        
                        $modelUsersData->id = $model->id;
                        $modelUsersData->activation_code = sha1(mt_rand(1, 99999).time().$model->email);
                        if($modelUsersData->save()){
                            $this->redirect(array('viewMyPrivateProfile','id'=>$model->id));
                        }
                    }
                }
            }

            $this->render('create',array(
                'model'=>$model,
                'modelUsersData'=>$modelUsersData
            ));
        }else{
            $this->redirect(array('viewProfile','id'=>Yii::app()->user->id));
        }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		// every user has the right to update its own profile...
        // beside, the users with the right "users_profile_update" also has the right to update other users profile...
        if( (Yii::app()->user->id === $id) || Yii::app()->user->checkAccess('users_profile_update')){
            $model=$this->loadModel($id);
            $modelUsersData=UsersData::model()->findByPk($id);
                        
            $model->scenario = 'update';

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Users']))
            {
                
                if( (Yii::app()->user->id === $id && isset($_POST['Users']['status']) && (int)$_POST['Users']['status']<0)){
                    $_POST['Users']['status'] = $model->status; // set the new statu to the preview status...
                    Yii::app()->user->setFlash('error', "You can not block your own account!");
                }
                    
                $model->attributes=$_POST['Users'];

                // if isset($_POST['Users']) is true, this should be also true...
                if(isset($_POST['UsersData'])){
                    $modelUsersData->attributes=$_POST['UsersData'];            
                    $modelUsersData->validate(); // in order to print errors for this model as well
                    
                    if($model->save()){
                        if($modelUsersData->save()){
                            $this->redirect(array('viewMyPrivateProfile','id'=>$model->id));
                        }
                    }
                }
            }

            $this->render('update',array(
                'model'=>$model,
                'modelUsersData'=>$modelUsersData,
                'myEmails'=> BumUserEmails::findMyEmails($model->id), 
                'hasUnverifiedEmails'=>  Emails::hasUnverifiedEmails($model->id),
            ));
        }else{
            $this->redirect(array('viewMyPrivateProfile','id'=>$id));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        if( ( Yii::app()->user->checkAccess('users_delete')) ){
            if (Yii::app()->user->id == $id) {
                Yii::app()->user->setFlash('error', "You can not delete your own account!");
                $this->redirect(array('viewProfile','id'=>$id));
            }else{
                $this->loadModel($id)->delete();

                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if(!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                }
            }
        }else{
            $this->redirect(array('viewProfile','id'=>$id));
        }
	}

	/**
	 * Lists all models.
     * View all users; useless page..
	 */
	public function actionViewAllUsers()
	{
        if( ( Yii::app()->user->checkAccess('users_all_view')) ){
            $model=new Users('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Users']))
                $model->attributes=$_GET['Users'];

            $this->render('viewAllUsers',array(
                'model'=>$model,
            ));
        }else{
            $this->redirect(array('viewProfile','id'=>Yii::app()->user->id));
        }
	}

	/**
	 * Manages all models.
     * List search and print options (update; delete) for all users.
	 */
	public function actionAdmin()
	{
        if( (Yii::app()->user->checkAccess('users_admin')) ){
            $model=new Users('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Users']))
                $model->attributes=$_GET['Users'];

            $this->render('admin',array(
                'model'=>$model,
            ));
        }else{
            $this->redirect(array('viewProfile','id'=>Yii::app()->user->id));
        }
	}

    /**
     * Sign up action.
     */
    public function actionSignUp()
    {
        if (Yii::app()->getModule('bum')->enabledSignUp) {
            $model=new Users;
            $model->email = ""; // because default is "noEmail"
            $modelUsersData=new UsersData;
            $modelEmails = new Emails;
            $invitation = new Invitations;
            $validInvitation = false;

            $model->scenario = 'signUp';
            $invitation->scenario = 'signUp';

            if (isset($_GET['invitationCode'])) {
                $invitation->invitation_code = $_GET['invitationCode'];
            }
            if (isset($_GET['email'])) {
                $model->email = $_GET['email'];
            }

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Users']))
            {
                $model->attributes=$_POST['Users'];

                // check the invitation code
                $invitation->attributes = $_POST['Invitations'];
                $invitation->email = $model->email;
                $invitation->setIsNewRecord(false); // because in this case this in not a new record; should be treated as an existing record

                $modelUsersData->id = 0; // to pass the validation; to see if there are other problems..
                if($invitation->validate() && $modelUsersData->validate() && $model->save()){

                    $modelUsersData->invitations_left=Yii::app()->getModule('bum')->invitationDefaultNumber;            
                    $modelUsersData->id = $model->id;
                    $invitation->id_user_invited = $model->id;

                    $modelUsersData->activation_code = sha1(mt_rand(1, 99999).time().$model->email);

                    if($invitation->save(false) && $modelUsersData->save()){

                        // automatically insert this email into emails table!
                        $modelEmails->id_user = $model->id;
                        $modelEmails->name = $model->email;
                        $modelEmails->save();

                        $message = $this->sendSignUpEmail($modelUsersData);

                        if(Yii::app()->mail->send($message)){
                            Yii::app()->user->setFlash('success', "A comfirmation email has been sent to the provided email address!");
                            //Yii::app()->user->setFlash('success', "<p>{$message->body}</p>");
                            $this->redirect(array('/site/index'));
                        }else{
                            Yii::app()->user->setFlash('error', "A comfirmation email could not been send to the provided email address!");
                            $this->redirect(array('users/resendSignUpConfirmationEmail'));
                        }
                    }
                }
            }

            $model->invitations = $invitation;

            $this->render('signUp',array(
                'model'=>$model,
            ));
        }else{
            Yii::app()->user->setFlash('notice', "SignUp is disabled! No SingUp is allowed!");
            $this->render('noSignUp');
        }
    }
    
    /**
     * Resend the activation email!
     */
    public function actionResendSignUpConfirmationEmail()
    {
        $model=new FindUserBy;
        $model->scenario = 'resendSignUpConfirmationEmail';

        // uncomment the following code to enable ajax-based validation
        /*
        if(isset($_POST['ajax']) && $_POST['ajax']==='resend-sign-up-confirmation-email-resendSignUpConfirmationEmail-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        */

        if(isset($_POST['FindUserBy']))
        {
            $model->attributes=$_POST['FindUserBy'];
            if($model->validate())
            {
                if ($model->usersData === NULL){
                    // shouldn't reach here!
                    Yii::app()->user->setFlash('error', "No data found!");
                    $this->redirect(array('/site/index'));
                }else{
                    $message = $this->sendSignUpEmail($model->usersData);
                    
                    if(Yii::app()->mail->send($message)){
                        Yii::app()->user->setFlash('success', "The comfirmation email has been resent to the provided email address!");
                        //Yii::app()->user->setFlash('success', "<p>{$message->body}</p>");
                        $this->redirect(array('/site/index'));
                    }else{
                        // something went wrong...
                        Yii::app()->user->setFlash('error', "Confirmation email coud not be resent!");
                        $this->redirect(array('/site/index'));
                    }
                }
            }
        }
        $this->render('resendSignUpConfirmationEmail',array('model'=>$model));
    }    
    
    /**
     * Password recovery; find user!
     */
    public function actionPasswordRecoveryWhatUser()
    {
        $model=new FindUserBy;
        $model->scenario = 'passwordRecoveryWhatUser';

        // uncomment the following code to enable ajax-based validation
        /*
        if(isset($_POST['ajax']) && $_POST['ajax']==='email-passwordRecovery-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        */

        if(isset($_POST['FindUserBy']))
        {
            $model->attributes=$_POST['FindUserBy'];
            if($model->validate())
            {
                $modelPasswordRecovery = new PasswordRecovery;
                
                $modelPasswordRecovery->id_user = $model->user->id;
                $modelPasswordRecovery->code = substr(md5(uniqid(rand(), true)),0,6); //generate 6 digits code
                $modelPasswordRecovery->long_code = uniqid('', true); //generate  unique code
                $modelPasswordRecovery->ip = Yii::app()->request->userHostAddress;
                $modelPasswordRecovery->email = $model->user->email;
                $modelPasswordRecovery->user_name = $model->user->user_name;
                   
                if($modelPasswordRecovery->save()){
                    $message = $this->sendPasswordRecoveryEmail($modelPasswordRecovery);

                    if(Yii::app()->mail->send($message)){
                        Yii::app()->user->setFlash('success', "Check your email - a link to to a password recovery page was sent to you!");
                        //Yii::app()->user->setFlash('success', "<p>{$message->body}</p>");
                        $this->redirect(array('users/passwordRecoveryAskCode', 'lc'=>$modelPasswordRecovery->long_code, 'em'=> md5($modelPasswordRecovery->email)));
                    }else{
                        // something went wrong...
                        Yii::app()->user->setFlash('error', "Password recovery email could not be sent; please try again later!");
                        $this->redirect(array('/site/index'));
                    }
                }else{
                    $errors = $modelPasswordRecovery->getErrors();
                    
                    // something went wrong...
                    if(Yii::app()->getModule('bum')->demoMode){
                        Yii::app()->user->setFlash('notice', "Demo mode is active! No changes allowed!");
                    }else{                    
                        Yii::app()->user->setFlash('error', "Password recovery email could not be sent; please try again later!");
                    }
                    $this->redirect(array('/site/index'));
                }
            }
        }
        $this->render('passwordRecoveryWhatUser',array('model'=>$model));
    }    
    
    /**
     * Send the reset your password email
     * Uses Yii-Mail extension.
     * 
     * @param type $password_recovery
     * @return \YiiMailMessage
     */
    public function sendPasswordRecoveryEmail($model){
        $message = new YiiMailMessage;
        
        $message->view = 'passwordRecovery';
        $message->setBody(array('modelPasswordRecovery'=>$model), 'text/html');
        $message->subject = 'You requested a new password‏';
        $message->addTo($model->email);
        $message->from = $this->module->passwordRecoveryEmail;
        
        return $message;
    }
    
    /**
     * Ask for password recovery code; search it in the database; if found ask for new password; redirect to login page.
     * @param type $lc
     * @param type $em
     * @param type $code
     * @throws CHttpException
     */
    public function actionPasswordRecoveryAskCode($lc, $em, $code = ''){
        // find if there is an active request for password reset having this credentials;
        $modelPasswordRecovery = PasswordRecovery::model()->findByAttributes(array("long_code"=>$lc, "used"=>FALSE, "expired"=>FALSE), 'MD5(email)=:em', array(':em'=>$em));
        
        if($modelPasswordRecovery === NULL){
            // should'n reach here; unwanted attempt to recover a password...
			throw new CHttpException(404,'The requested page does not exist.');
        }else{
            // check if the password request link is still active
            $secFromPasswordRecoveryCreation = (time() - strtotime($modelPasswordRecovery->date_of_request));
            $hoursFromPasswordRecovery = round($secFromPasswordRecoveryCreation/(60*60));

            // if the link is not active:
            if ($hoursFromPasswordRecovery >= $this->module->hoursPasswordRecoveryLinkIsActive) {
                // delete PasswordRecovery model data, or set it as expired...
                if ($this->module->trackPasswordRecoveryRequests) {
                    $modelPasswordRecovery->expired = TRUE;
                    $modelPasswordRecovery->save();
                }else{
                    $modelPasswordRecovery->delete();
                }
    			throw new CHttpException(404,'The requested page does not exist.');
            }else{
                
                // an code vas inserted; test if the code is the same as the code from the data base
                if(isset($_POST['PasswordRecovery'])){
                    $attributes = $_POST['PasswordRecovery'];
                    $modelPasswordRecovery->code_inserted = $attributes['code_inserted'];
                    
                    $modelPasswordRecovery->scenario = 'askCode';
                    // the code is valide:
                    if($modelPasswordRecovery->validate()){
                        
                        $modelUsers=$this->loadModel($modelPasswordRecovery->id_user);
                        $modelUsers->scenario = 'passwordReset';
                        
                        // a new password was set
                        if(isset($_POST['Users'])){
                            $attributes = $_POST['Users'];
                            $modelUsers->password = $attributes['password'];
                            $modelUsers->password_repeat = $attributes['password_repeat'];
                            
                            // the new password is a valid password:
                            if($modelUsers->validate()){
                                // save the new password
                                $modelUsers->save();
                                // set this password recovery link as used:
                                $modelPasswordRecovery->used = TRUE;
                                $modelPasswordRecovery->save();
                                
                                Yii::app()->user->setFlash('notice', "Your password had been reseted! You may now log in using your new password!");
                                // redirect to the logIn page
                                $this->redirect(array('/bum/users/login'));
                            }
                        }

                        // go to password reset page
                        $this->render('passwordRecoveryResetPassword', array(
                            'modelPasswordRecovery' => $modelPasswordRecovery,
                            'modelUsers'=>$modelUsers,
                        ));
                        Yii::app()->end();
                    }
                }
                
                if (strlen(trim($code)) > 0){
                    $modelPasswordRecovery->code_inserted = $modelPasswordRecovery->code;
                }
                
                $this->render('passwordRecoveryAskCode', array(
                    'modelPasswordRecovery' => $modelPasswordRecovery
                ));
            }
        }
        
    }
    
    /**
     * Send the Sign Up confirmation email
     * Uses Yii-Mail extension.
     * 
     * @param type $userModel
     * @return \YiiMailMessage
     */
    public function sendSignUpEmail($model){
        $message = new YiiMailMessage;
        
        $message->view = 'signUpEmail';
        $message->setBody(array('modelUsersData'=>$model), 'text/html');
        $message->subject = 'activation email';
        $message->addTo($model->users->email);
        $message->from = $this->module->notificationSignUpEmail;
        
        return $message;
    }
    
    /**
     * Check and activate the account.
     * @param type $acKey
     */
    public function actionActivate($acKey){
        
        $modelUsersData=UsersData::model()->findByAttributes(array('activation_code' => $acKey));
        
        if ($modelUsersData === NULL) {
            Yii::app()->user->setFlash('notice', "There is no user with this code to be activated, or your activation period has expire!");
            $this->redirect(array('/site/index'));
        }else{
            $model=$this->loadModel($modelUsersData->id);
            if ($model->active) {
                // this account was already activated
                Yii::app()->user->setFlash('notice', "This account is active!");
                $this->redirect(array('/site/index'));
            }else{
                // the check should be made using chron
                // check if the link is still active
                //$secFromAccCreation = (time() - strtotime($model->date_of_creation));
                //$hoursFromAccCreation = round($secFromAccCreation/(60*60));
                //if ($hoursFromAccCreation > $this->module->hoursActivationLinkIsActive && $this->module->hoursActivationLinkIsActive > 0) {
                    // delete the user; 
                    // $model->delete();
                    // unactivated user are deleted from chronos
                    // @todo: also when checking for user availability if the user is unactivated and the time is expired, the position should not be ocupied
                    
                //    Yii::app()->user->setFlash('notice', "There is no user with this code to be activated, or your activation period has expire!");
                //    $this->redirect(array('/site/index'));
                //}else{
                    // confirm the activation and sugest to user to log in
                    $model->active = true;
                    $model->save(false);
                    
                    // automatically confirm/verify this email!
                    $modelEmailsArray = Emails::model()->findAllByAttributes(array('name'=>$model->email, 'id_user'=>$model->id));
                    foreach ($modelEmailsArray as $modelEmails){
                        $modelEmails->verified = true;
                        $modelEmails->save(false);
                    }

                    $linkToLoginPage = CHtml::link('Login', $this->createAbsoluteUrl('/bum/users/login'));
                    Yii::app()->user->setFlash('success', "Account has been activated! You may now {$linkToLoginPage}.");

                    $this->redirect(array('/site/index'));
                //}
            }   
        }
        
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
