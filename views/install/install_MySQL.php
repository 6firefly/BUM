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
	'Install' => array('install/'),
	'Install MySQL',
);

$this->menu=array(    
	array('label'=>'Install with MySQL', 'url'=>array('install/MySQL'), 'visible'=>($this->module->install)),
	array('label'=>'Install with PostgreSQL', 'url'=>array('install/PostgreSQL'), 'visible'=>($this->module->install)),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>($this->module->install)), // separator
    
	array('label'=>'Usefull things (How to?)', 'url'=>array('install/howTo'), 'visible'=>($this->module->install)),
);

?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<OL>
    <LI>
        <p>
            The sql scripts required for this module must be installed separately!<BR/>
            Create a database, then run the sql scripts.<BR/>
            The sql script can be found in:<BR/>
            <UL>
                <LI><?php echo CHtml::link("bum/install/install.v2.03.MySQL.sql", $this->createUrl("install/MySQL_scripts"), array("target"=>"_blank")); ?></LI>
                <LI><A href="https://code.google.com/p/yii/source/browse/trunk/framework/web/auth/schema-mysql.sql?r=3293" target="_blank">framework/web/auth/schema-mysql.sql</A>.</LI>
            </UL>
            <BR/>
            InnoDB engine must be enabled. The sql scripts make use of triggers, and relational tables.<BR/>
            <BR/>
            <I>
                Do not forget to connect the application to the database!<br/>
                Example of how to connect to the database:
                <UL>
                    <li>
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
                </UL>
            </I>
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            This module uses the <A href="http://www.yiiframework.com/extension/mail/" target="_blank">yii-mail</A> module 
            in order to sent confirmation email to the users. So please download and install this module also.
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            <B>Yii configurations</B> <I>(in config/main.php)</I>:
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
    <A id="authManager" name="authManager"></A>
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
            <B>After completed the above steps</B>, please set the corresponding roles as follow:
        </p>
        <p>
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
                                <li>password_change <SMALL>operation</SMALL> With this right user can change the password without knowing the old password.</li>
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
            echo CHtml::AjaxLink('create/update role/tasks/operations', array("install/InstallRights"), array('update'=>'#defaultRights'), array('id'=>'CreateAuthRights', 'live'=>false));
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
    <LI>
        <p>
            If you want to delete users that are not active, please add action <b>actionDeleteUnactivateUsers</b> from <b>CronController</b> to your cron. (<?php echo CHtml::link('/cron/deleteUnactivateUsers', $this->createUrl('cron/deleteUnactivateUsers'), array('target'=>'_blank'))?>)
        </p>
        <p>
            If you want to delete unconfirmed emails, please add action <b>actionDeleteUnverifiedEmails</b> from <b>CronController</b> to your cron.(<?php echo CHtml::link('/cron/deleteUnverifiedEmails', $this->createUrl('cron/deleteUnverifiedEmails'), array('target'=>'_blank'))?>)
        </p>
        <HR/>
        <HR/>
    </LI>
</OL>
<UL>
    <LI>
        <p>
            This module uses the default authorizing manager 
            (<A href="http://www.yiiframework.com/doc/api/1.1/CDbAuthManager" target="_blank">CDbAuthManager</A>).
        </p>

        <HR/>
    </LI>
    <LI>
        <p>
            Here is an example of how to include BUM module into your main menu (<I>protected/views/layouts/main.php</I>):
<PRE class="box small">
&LT;?php $this->widget('zii.widgets.CMenu',array(
    'items'=>array(
        ...
        array('label'=>'Install', 'url'=>array('/bum/install'), 'visible'=>Yii::app()->getModule('bum')->install),
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
</UL>
