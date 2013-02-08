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
    
	public function actionIndex()
	{
		if ($this->module->install === true) {
            $this->render('install_notices');
        }else{
            $this->render('install_notices');
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
                
                $operation = $auth->createOperation('users_admin', 'View all users + options.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('users_create', 'Create a user.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('users_delete', 'Delete a user.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('users_all_view', 'View all users.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('users_profile_update', 'Update a user profile.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('users_all_privateProfile_view', 'View a user private profile.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");

                $task = $auth->createTask('users_manage', 'Manage users!');
                $task->addChild('users_admin');
                $task->addChild('users_create');
                $task->addChild('users_delete');
                $task->addChild('users_all_view');
                $task->addChild('users_profile_update');
                $task->addChild('users_all_privateProfile_view');
                
                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => true,
                        'children' => $operationTree);

                $operationTree = array();
                
                $operation = $auth->createOperation('emails_create', 'Create a secondary email.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('emails_verificationLink_resend', 'Resend the verification link.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('emails_delete', 'Delete a secondary email.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");
                $operation = $auth->createOperation('emails_all_view', 'View all emais.');
                    $operationTree[] = array('text'=>"<B>{$operation->name}</B> operation <I>{$operation->description}</I>");

                $task = $auth->createTask('emails_manage', 'Manage secondary emails!');
                $task->addChild('emails_create');
                $task->addChild('emails_verificationLink_resend');
                $task->addChild('emails_delete');
                $task->addChild('emails_all_view');

                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => true,
                        'children' => $operationTree);
                        
                $task = $auth->createTask('settings_manage', 'Allow the user to change the default settings.');
                
                $taskTree[] = array(
                        'text' => "<B>{$task->name}</B> task <I>{$task->description}</I>", 
                        'hasChildren' => false);
                
                $role = $auth->createRole(BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN, 'The most powerful admin!');
                $role->addChild('emails_manage');
                $role->addChild('users_manage');
                $role->addChild('settings_manage');
            
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
                    throw new CException('Please configure authManager to user CDbAuthManager; see instalation notes!');
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