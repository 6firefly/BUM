<?php
/**
 * InstallController class file.
 * Controller class file for installing this module.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * InstallController class.
 * @package		bum
 */

class InstallController extends BumController
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
			array('allow',  // 
				'actions'=>array('index', 'MySQL', 'PostgreSQL', 'InstallRights', 'InstallDefaultUser', 'MySQL_scripts', 'PostgreSQL_scripts', 'howTo'),
				'expression'=>'Yii::app()->getModule("bum")->install',
			),
			array('allow',  // 
				'actions'=>array('upgrade'),
				'expression'=>'Yii::app()->getModule("bum")->install',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
    /**
     * About BUM.
     */
	public function actionIndex()
	{
		if ($this->module->install === true) {
            $this->render('install_notices');
        }else{
        }
	}
    
    /**
     * Usefull things.
     */
	public function actionHowTo()
	{
		if ($this->module->install === true) {
            $this->render('howTo');
        }else{
        }
	}

    /**
     * MySQL script file..
     */
	public function actionMySQL_scripts()
	{
		if ($this->module->install === true) {
            $this->render('MySQL_scripts');
        }else{
        }
	}
    
    /**
     * How to install BUM with MySQL database server..
     */
	public function actionMySQL()
	{
		if ($this->module->install === true) {
            $this->render('install_MySQL');
        }else{
        }
	}

    /**
     * MySQL script file..
     */
	public function actionPostgreSQL_scripts()
	{
		if ($this->module->install === true) {
            $this->render('PostgreSQL_scripts');
        }else{
        }
	}

    /**
     * How to install BUM with PosgreSQL database server..
     */
	public function actionPostgreSQL()
	{
		if ($this->module->install === true) {
            $this->render('install_PostgreSQL');
        }else{
        }
	}

	public function actionInstallRights()
	{
		if ($this->module->install) {
            $connection = Yii::app()->db;
            $transaction=$connection->beginTransaction();
            $rights = array();
            try {
                $auth = Yii::app()->authManager;
                if(get_class($auth) == "CPhpAuthManager"){
                    throw new CException('Please configure authManager to user CDbAuthManager; see instalation notes!');
                }
                
                $roleTree = array();
                $taskTree = array();
                $operationTree = array();
                
                if(!$auth->getAuthItem('users_admin')){
                    $operation = $auth->createOperation('users_admin', 'View all users + options.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('users_create')){
                    $operation = $auth->createOperation('users_create', 'Create a user.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('users_delete')){
                    $operation = $auth->createOperation('users_delete', 'Delete a user.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('users_all_view')){
                    $operation = $auth->createOperation('users_all_view', 'View all users.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('users_profile_update')){
                    $operation = $auth->createOperation('users_profile_update', 'Update a user profile.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('users_all_privateProfile_view')){
                    $operation = $auth->createOperation('users_all_privateProfile_view', 'View a user private profile.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('password_change')){
                    $operation = $auth->createOperation('password_change', 'With this right user can change the password without knowing the old password.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }

                if(!$auth->getAuthItem('users_manage')){
                    $task = $auth->createTask('users_manage', 'Manage users!');
                }else{
                     $task = $auth->getAuthItem('users_manage');
                }
                if(!$task->hasChild('users_admin')) $task->addChild('users_admin');
                if(!$task->hasChild('users_create')) $task->addChild('users_create');
                if(!$task->hasChild('users_delete')) $task->addChild('users_delete');
                if(!$task->hasChild('users_all_view')) $task->addChild('users_all_view');
                if(!$task->hasChild('users_profile_update')) $task->addChild('users_profile_update');
                if(!$task->hasChild('users_all_privateProfile_view')) $task->addChild('users_all_privateProfile_view');
                if(!$task->hasChild('password_change')) $task->addChild('password_change');
                
                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => true,
                        'children' => $operationTree);

                $operationTree = array();
                
                if(!$auth->getAuthItem('emails_create')){
                    $operation = $auth->createOperation('emails_create', 'Create a secondary email.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('emails_verificationLink_resend')){
                    $operation = $auth->createOperation('emails_verificationLink_resend', 'Resend the verification link.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('emails_delete')){
                    $operation = $auth->createOperation('emails_delete', 'Delete a secondary email.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }
                if(!$auth->getAuthItem('emails_all_view')){
                    $operation = $auth->createOperation('emails_all_view', 'View all emais.');
                        $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                }

                if(!$auth->getAuthItem('emails_manage')){
                    $task = $auth->createTask('emails_manage', 'Manage secondary emails!');
                }else{
                     $task = $auth->getAuthItem('emails_manage');
                }
                if(!$task->hasChild('emails_create')) $task->addChild('emails_create');
                if(!$task->hasChild('emails_verificationLink_resend')) $task->addChild('emails_verificationLink_resend');
                if(!$task->hasChild('emails_delete')) $task->addChild('emails_delete');
                if(!$task->hasChild('emails_all_view')) $task->addChild('emails_all_view');

                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => true,
                        'children' => $operationTree);
                        
                if(!$auth->getAuthItem('settings_manage')){
                    $task = $auth->createTask('settings_manage', 'Allow the user to change the default settings.');
                }else{
                     $task = $auth->getAuthItem('settings_manage');
                }
                
                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => false);
                
                if(!$auth->getAuthItem(BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN)){
                    $role = $auth->createRole(BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN, 'The most powerful admin!');
                }else{
                     $role = $auth->getAuthItem(BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN);
                }
                if(!$role->hasChild('emails_manage')) $role->addChild('emails_manage');
                if(!$role->hasChild('users_manage')) $role->addChild('users_manage');
                if(!$role->hasChild('settings_manage')) $role->addChild('settings_manage');
            
                $roleTree[] = array(
                        'text' => "<B>{$role->name}</B> role <I>{$role->description}</I>", 
                        'hasChildren' => true,
                        'children' => $taskTree);
                        
                // commit transaction
                $transaction->commit();
                $rights = $roleTree;
                
            } catch(Exception $e) {
                $rights = array();
                $transaction->rollBack();                
                Yii::app()->user->setFlash('notice', "Rights (role/tasks/operations) could not be implemented: " . $e->getMessage() . " !");
            }                
            $this->renderPartial('install_notices_rights', array(
                'rights' => $rights,
            ));
        }else{
            Yii::app()->user->setFlash('notice', "In order for the install to procede property should be: <B>'install'=>true</B> see documentation.");
            $this->renderPartial('install_notices_rights', array(
                'rights' => array(),
            ));
        }
	}

	public function actionInstallDefaultUser()
	{
		if ($this->module->install) {
            $defaultUsers = array();
            
            $connection=Yii::app()->db;
            $transaction=$connection->beginTransaction();
            try {       
                // insert the default admin user
                // --------------------------- START ---------------------------                
                $modelSuperAdminUser = new Users;
                $defaultSuperAdminUserData = BumDefaultInstallData::getDefalutSuperAdminUserData();
                
                $modelSuperAdminUser->user_name = $defaultSuperAdminUserData['user_name'];
                $modelSuperAdminUser->salt = Users::generateSalt();
                $modelSuperAdminUser->pass = $modelSuperAdminUser->hashPassword($modelSuperAdminUser->user_name, $modelSuperAdminUser->salt);
                
                $modelSuperAdminUser->name = $defaultSuperAdminUserData['name'];
                $modelSuperAdminUser->surname = $defaultSuperAdminUserData['surname'];
                $modelSuperAdminUser->active = true;
                $modelSuperAdminUser->email = $defaultSuperAdminUserData['email'];
                
                $modelSuperAdminUser->save();
                
                $modelUsersData = new UsersData;
                $modelUsersData->id = $modelSuperAdminUser->id;
                $modelUsersData->description = $defaultSuperAdminUserData['description'];
                $modelUsersData->activation_code = sha1(mt_rand(1, 99999).time().$modelSuperAdminUser->email);
                $modelUsersData->save();
                
                $modelEmails = new Emails;
                // automatically insert this email into emails table!
                $modelEmails->id_user = $modelSuperAdminUser->id;
                $modelEmails->name = $modelSuperAdminUser->email;
                $modelEmails->save();

                $auth=Yii::app()->authManager;
                if(get_class($auth) == "CPhpAuthManager"){
                    throw new CException('Please configure authManager to user CDbAuthManager; see installation notes!');
                }
                $assignmentTable = $auth->assignmentTable;                
                $sql = "INSERT INTO {$assignmentTable} (itemname, userid) 
                    SELECT :role, id FROM users WHERE user_name = :userName";
                $command=$connection->createCommand($sql);
                $command->bindValue(":userName", $defaultSuperAdminUserData['user_name'],PDO::PARAM_STR);
                $command->bindValue(":role", BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN,PDO::PARAM_STR);
                $command->execute();
                // --------------------------- END ---------------------------
                $defaultUsers[] = array('user_name'=> $defaultSuperAdminUserData['user_name'], 'pass'=>$defaultSuperAdminUserData['user_name'], 'email'=>$defaultSuperAdminUserData['email']);
                
                // insert the default demo user
                // --------------------------- START ---------------------------
                $modelDemoUser = new Users;
                $defaultDemoUserData = BumDefaultInstallData::getDefalutDemoUserData();
                $modelDemoUser->user_name = $defaultDemoUserData['user_name'];
                $modelDemoUser->salt = Users::generateSalt();
                $modelDemoUser->pass = $modelDemoUser->hashPassword($modelDemoUser->user_name, $modelDemoUser->salt);
                
                $modelDemoUser->name = $defaultDemoUserData['name'];
                $modelDemoUser->surname = $defaultDemoUserData['surname'];
                $modelDemoUser->active = true;
                $modelDemoUser->email = $defaultDemoUserData['email'];
                
                $modelDemoUser->save();
                
                $modelUsersData = new UsersData;
                $modelUsersData->id = $modelDemoUser->id;
                $modelUsersData->description = $defaultDemoUserData['description'];;
                $modelUsersData->activation_code = sha1(mt_rand(1, 99999).time().$modelDemoUser->email);
                $modelUsersData->save();
                
                $modelEmails = new Emails;
                // automatically insert this email into emails table!
                $modelEmails->id_user = $modelDemoUser->id;
                $modelEmails->name = $modelDemoUser->email;
                $modelEmails->save();
                // --------------------------- END ---------------------------
                $defaultUsers[] = array('user_name'=> $defaultDemoUserData['user_name'], 'pass'=>$defaultDemoUserData['user_name'], 'email'=> $defaultDemoUserData['email']);
                
                // commit transaction
                $transaction->commit();

            } catch(Exception $e) {
                $transaction->rollBack();                
                Yii::app()->user->setFlash('notice', "The default user could not be created: " . $e->getMessage() . " !");
            }

            $this->renderPartial('install_notice_default_users', array(
                'defaultUsers' => $defaultUsers,
            ));
            
        }else{
            Yii::app()->user->setFlash('notice', "In order for the install to procede property should be: <B>'install'=>true</B> see documentation below.");
            $this->renderPartial('install_notice_default_users', array(
                'defaultUsers' => array(),
            ));
        }
    }
    
    public function actionUpgrade(){
		if ($this->module->install === true) {
            $this->render('upgrade');
        }else{
        }
    }
    
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}