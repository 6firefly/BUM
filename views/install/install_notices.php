<?php
/**
 * Install controller main view file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License
 *  
 */

/* @var $this InstallController */

$this->breadcrumbs=array(
	'Install',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>Basic User Management (BUM) module aims to be a simple and easy to use module, but in the same time a powerful one in managing users.<br/>
The module does not intend to "reinvent the wheel", so whenever a task not related with user management is needed, 
it make use of other great modules (like yii-mail and/or RBAM).<br/>
This module has a simple and intuitive administration panel and response to basic user administration needs. Enjoy it.<BR/>
<BR/>
Feedback is welcomed. :)<BR/>
</p>

<hr/>

<OL>
    <LI>
        <p>
            The sql script required for this module must be installed separately!<BR/>
            Create a database, then run the sql scripts.<BR/>
            The sql script can be found in:<BR/>
            &nbsp;&nbsp; bum/install/install.MySQL.sql - for MySQL server<BR/>
            <I>
                &nbsp;&nbsp;&nbsp;&nbsp; or in<BR/>
                &nbsp;&nbsp; bum/install/install.postgre.sql - for PostgreSQL server<BR/>
            </I>
            <BR/>
            If you want to use this module with MySQL, InnoDB engine must be enabled. The sql scripts make use of triggers, and relational tables.<BR/>
            <BR/>
            Do not forget to connect the application to the database!<br/>
            Example of how to connect to the database:
            <UL>
                <li>for MySQL:
<PRE class="box small">
'db'=>array(
    'tablePrefix'=>'',
    'connectionString' => 'mysql:host=localhost;dbname=myDatabase',
    'username' => 'root',
    'password' => 'password',
    'charset' => 'utf8',
),
</PRE>
                </li>
                <li><I>for PostgreSQL:
<PRE class="box small">
'db'=>array(
        'tablePrefix'=>'',
        'connectionString' => 'pgsql:host=localhost;port=5432;dbname=myDatabase',
        'username'=>'username',
        'password'=>'password',
        'charset'=>'UTF8',
),
</PRE>
                </I></li>
            </UL>
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            This module uses the default authorizing manage 
            (<A href="http://www.yiiframework.com/doc/api/1.1/CDbAuthManager" target="_blank">CDbAuthManager</A>) 
            the default auth manager sql script can be found in: framework/web/auth/schema-mysql.sql for MySQL or in 
            framework/web/auth/schema-pgsql.sql for Postgresql.<BR/><BR/>
            Do not forget to configure the CDbAuthManager.
            <UL>
                <li><I>If you want to user PostgreSQL and RBAC module, please see <A href="#RBAM_module">observation</A>.</I></li>
            </UL>
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            This module uses the <A href="http://www.yiiframework.com/extension/mail/" target="_blank">yii-mail</A> module 
            order to sent confirmation email to the users. So please download and install this module also.
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
        The following configurations must be apply in order for the module to work properly:
<PRE class="box small">
// autoloading model and component classes
'import'=>array(
    ...
    'ext.yii-mail.YiiMailMessage', // module yii-mail is required in order to sent confirmation email to the users

    'application.modules.bum.models.*',
    'application.modules.bum.components.*',
),
...
'modules'=>array(
    ...
    // Basic User Management module;
    'bum' => array(
        // needs yii-mail extension..
        // 
        'install'=>true, // true just for installation mode, on develop or production mode should be set to false
    ),
    ...
);
...
// application components
'components'=>array(
    ...
    'user'=>array(
        ...
        'class' => 'BumWebUser',
        'loginUrl' => array('//bum/users/login'), // required
        ...
    ),

    ...
    'authManager'=>array(
        'class'=>'CDbAuthManager',
        'connectionID'=>'db',
    ),
    ...
    // required by bum module
    'mail' => array(
        'class' => 'ext.yii-mail.YiiMail', //  module yii-mail is required in order to sent confirmation emails to the users
        'transportType' => 'php',
        'viewPath' => 'bum.views.mail',
        'logging' => false,
        'dryRun' => false,
    ),
    ...
),
</PRE>
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            Here is an example of how to include BUM module into your main menu <SPAN class="small">protected/views/layouts/main.php</SPAN>:
<PRE class="box small">
&LT;?php $this->widget('zii.widgets.CMenu',array(
    'items'=>array(
        ...
        array('label'=>'My Profile', 'url'=>array('/bum/users/viewProfile', 'id'=>Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
        ...
        array('label'=>'Login', 'url'=>array('/bum/users/login'), 'visible'=>Yii::app()->user->isGuest),
        array('label'=>'SingUp', 'url'=>array('/bum/users/signUp'), 'visible'=>Yii::app()->user->isGuest),
        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
    ),
)); ?>
</PRE>
        </p>

        <HR/>
    </LI>
    <LI>
        <p><I>
            If you want to use RBAM module with this module then RBAM module needs the following setups:
<PRE class="box small">
'modules'=>array(
    'rbam'=>array(
        // RBAM Configuration
        'userClass'=>'Users', // BUM user class
        'userNameAttribute'=>'user_name', // BUM default user name attribute
    ),
),
</PRE>
        <A href="" id="RBAM_module"></A>OBSERVATIONS: In order to make module RBAM to work with PostgreSQL the following table names should be changed:
        <UL>
            <LI>AuthAssignment  to auth_assignment</LI>
            <LI>AuthItemChild  to auth_item_child</LI>
            <LI>AuthItem  to auth_item</LI>
        </UL>
        and authManager array should be initialized like:
<PRE class="box small">
'authManager'=>array(
    'class'=>'CDbAuthManager',
    'connectionID'=>'db',
    'itemTable'=>'auth_item',
    'itemChildTable'=>'auth_item_child',
    'assignmentTable'=>'auth_item_child',
),
</PRE>
        </I></p>

        <HR/>
    </LI>
    <LI>
        <p>
            In order for this module to function properly the following role/tasks/operations should be created:
            <UL>
                <li>
                    <?php echo BumDefaultInstallData::DEFAULT_ROLE_SUPER_ADMIN; ?> <SMALL>role</SMALL>
                    <UL>
                        <li>
                            manageUsers <SMALL>task</SMALL>
                            <UL>
                                <li>users_admin <SMALL>operation</SMALL></li>
                                <li>users_create <SMALL>operation</SMALL></li>
                                <li>users_delete <SMALL>operation</SMALL></li>
                                <li>users_all_view <SMALL>operation</SMALL></li>
                                <li>users_profile_update <SMALL>operation</SMALL> Update other users profile...</li>
                                <li>users_all_privateProfile_view <SMALL>operation</SMALL> View other users private profile...</li>
                            </UL>

                        </li>
                        <li>
                            emails_manage <SMALL>task</SMALL>
                            <UL>
                                <li>emails_create <SMALL>operation</SMALL></li>
                                <li>emails_delete <SMALL>operation</SMALL></li>
                                <li>emails_all_view <SMALL>operation</SMALL></li>
                                <li>emails_verificationLink_resend <SMALL>operation</SMALL></li>
                            </UL>
                        </li>
                        <li>
                            settings_manage <SMALL>task</SMALL>
                        </li>
                    </UL>
                </li>
            </UL>
            <?php
            echo CHtml::AjaxLink('create role/tasks/operations', array("install/InstallRights"), array('update'=>'#defaultRights'), array('id'=>'CreateAuthRights', 'live'=>false));
            ?><DIV id="defaultRights"></DIV>
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            Also tow default users can be created once that module is installed:<?php
                $defaultSuperAdminUserData = BumDefaultInstallData::getDefalutSuperAdminUserData();
                $defaultDemoUserData = BumDefaultInstallData::getDefalutDemoUserData();
            ?><UL>
                <li><?php echo $defaultSuperAdminUserData['user_name']; ?></li>
                <li><?php echo $defaultDemoUserData['user_name']; ?></li>
            </UL>
            <?php echo CHtml::AjaxLink('create default users', array("install/InstallDefaultUser"), array('update'=>'#defaultUsers'), array('id'=>'CreateDefaultUsers', 'live'=>false)); ?>
            <DIV id="defaultUsers"></DIV>
        </p>

        <HR/>
    </LI>
</OL>
