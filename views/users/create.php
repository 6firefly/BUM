<?php
/**
 * Create a new user view file.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 *  
 * Menu links are displayed only if the user has the right to see those pages.
 */

/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>(Yii::app()->user->checkAccess("users:index")?array('index'):""),
	'Create',
);

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->getModule("bum")->assetsUrl . '/js/invitations.js');

$this->menu=array(
	array('label'=>'Install', 'url'=>array('install/index'), 'visible'=>(Yii::app()->getModule("bum")->install)),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->getModule("bum")->install)), // separator
    
	array('label'=>'Settings', 'url'=>array('settings/batchUpdate'), 'visible'=>(Yii::app()->user->checkAccess("settings_manage"))),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->user->checkAccess("settings_manage"))), // separator
    
	array('label'=>'My Profile (' . Yii::app()->user->name . ')', 'url'=>array('users/viewMyPrivateProfile', 'id'=>Yii::app()->user->id), 'visible'=>(Yii::app()->user->checkAccess("users_manage") && (!isset($model) || Yii::app()->user->id != $model->id))),
    
	array('template'=>'<HR style="margin:0 auto;"/>'), // separator
    
	array('label'=>'invite', 'url'=>'#', 
        'linkOptions'=>array(
                'onclick'=>'invitationDialog(
                        "' . Yii::app()->createUrl(Yii::app()->getModule("bum")->name . "/invitations/AJAXCreate", array('id_user'=>Yii::app()->user->id)) . '",
                        "' . Yii::app()->createUrl(Yii::app()->getModule("bum")->name . "/invitations/AJAXView", array('id_user'=>Yii::app()->user->id)) . '"
                    );' 
            ), 
        'visible'=>(!Yii::app()->user->isGuest && Yii::app()->getModule('bum')->invitationButtonDisplay)),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(!Yii::app()->user->isGuest && Yii::app()->getModule('bum')->invitationButtonDisplay)), // separator
    
	array('label'=>'Manage Users', 'url'=>array('users/admin'), 'visible'=>Yii::app()->user->checkAccess("users_admin")),
	array('label'=>'Create User', 'url'=>array('users/create'), 'visible'=>Yii::app()->user->checkAccess("users_create")),
	array('label'=>'View all Users', 'url'=>array('users/viewAllUsers'), 'visible'=>Yii::app()->user->checkAccess("users_all_view"), 'active'=>true),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->user->checkAccess('users_all_invites_view') && !Yii::app()->user->isGuest)), // separator
	array('label'=>'See Sent Invites', 'url'=>array('invitations/view'), 'visible'=>(Yii::app()->user->checkAccess('users_all_invites_view') && !Yii::app()->user->isGuest)),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->getModule("bum")->logInIfNotVerified && !Yii::app()->user->active && !Yii::app()->user->isGuest)), // separator
	array('label'=>'Resend Confirm. Email', 'url'=>array('users/resendSignUpConfirmationEmail'), 'visible'=>(Yii::app()->getModule("bum")->logInIfNotVerified && !Yii::app()->user->active && !Yii::app()->user->isGuest)),
);

/* Send an invitation dialog box */
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'Invite',
    'options'=>array(
        'autoOpen'=>false,
        'modal'=>true,
    )
));
    ?><DIV id="dlg_history_content"></DIV><?php
    ?><DIV id="dlg_invite_content"></DIV><?php
    ?><div id="AjaxLoader" style="display: none; margin: 0 auto; text-align: center;"><IMG src="<?php echo Yii::app()->getModule("bum")->assetsUrl; ?>/images/spinner.gif" width="60px" height="60px" /></div><?php
$this->endWidget('zii.widgets.jui.CJuiDialog');

?><h1>Create Users</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'modelUsersData'=>$modelUsersData)); ?>