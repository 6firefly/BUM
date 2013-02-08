<?php
/**
 * View my public profile.
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
	'Users'=>(Yii::app()->user->checkAccess("users_admin")?array('admin'):""),
	$model->user_name,
    'Public Profile',
);

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($this->module->assetsUrl . '/js/invitations.js');

$this->menu=array(
	array('label'=>'Install', 'url'=>array('install/index'), 'visible'=>($this->module->install)),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>($this->module->install)), // separator
    
	array('label'=>'Settings', 'url'=>array('settings/batchUpdate'), 'visible'=>(Yii::app()->user->checkAccess("settings_manage"))),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->user->checkAccess("settings_manage"))), // separator
    
	array('label'=>'My Profile (' . Yii::app()->user->name . ')', 'url'=>array('users/viewMyPrivateProfile', 'id'=>Yii::app()->user->id), 'visible'=>(Yii::app()->user->checkAccess("users_manage") && (!isset($model) || Yii::app()->user->id != $model->id))),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->user->checkAccess("users_manage") && (!isset($model) || Yii::app()->user->id != $model->id))), // separator
    
	array('label'=>$model->user_name, 'url'=>array('users/viewProfile', 'id'=>$model->id), 'items'=>array(
        array('label'=>'View Profile', 'url'=>array('users/viewProfile', 'id'=>$model->id), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'View Private Profile', 'url'=>array('users/viewMyPrivateProfile', 'id'=>$model->id), 'visible'=>((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('users_all_privateProfile_view'))),

        array('label'=>'Update Profile Information', 'url'=>array('users/update', 'id'=>$model->id), 'visible'=>((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('users_profile_update')), 'active'=>true),
        array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>Yii::app()->user->checkAccess("users_delete")),
    ), 'submenuOptions'=>array('style'=>'padding:0 0 0 15px;'), 'visible'=>!Yii::app()->user->isGuest),
    
	array('template'=>'<HR style="margin:0 auto;"/>'), // separator
    
	array('label'=>'invite', 'url'=>'#', 
        'linkOptions'=>array(
                'onclick'=>'invitationDialog(
                        "' . Yii::app()->createUrl($this->module->name . "/invitations/AJAXCreate", array('id_user'=>Yii::app()->user->id)) . '",
                        "' . Yii::app()->createUrl($this->module->name . "/invitations/AJAXView", array('id_user'=>Yii::app()->user->id)) . '"
                    );' 
            ), 
        'visible'=>(!Yii::app()->user->isGuest && Yii::app()->getModule('bum')->invitationButtonDisplay)),
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(!Yii::app()->user->isGuest && Yii::app()->getModule('bum')->invitationButtonDisplay)), // separator
    
	array('label'=>'Manage Users', 'url'=>array('users/admin'), 'visible'=>Yii::app()->user->checkAccess("users_admin")),
	array('label'=>'Create User', 'url'=>array('users/create'), 'visible'=>Yii::app()->user->checkAccess("users_create")),
	array('label'=>'View all Users', 'url'=>array('users/viewAllUsers'), 'visible'=>Yii::app()->user->checkAccess("users_all_view"), 'active'=>true),
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>($this->module->logInIfNotVerified && !Yii::app()->user->active && !Yii::app()->user->isGuest)), // separator
	array('label'=>'Resend Confirm. Email', 'url'=>array('users/resendSignUpConfirmationEmail'), 'visible'=>($this->module->logInIfNotVerified && !Yii::app()->user->active && !Yii::app()->user->isGuest)),
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
    ?><div id="AjaxLoader" style="display: none; margin: 0 auto; text-align: center;"><IMG src="<?php echo $this->module->assetsUrl; ?>/images/spinner.gif" width="60px" height="60px" /></div><?php
$this->endWidget('zii.widgets.jui.CJuiDialog');

?><h1>View Profile for User #<?php echo $model->user_name; ?></h1><?php

/*
 <div class="view span-17">


    <DIV class=" span-6"><?php echo CHtml::encode($model->getAttributeLabel('user_name')); ?></DIV>
    <DIV class=" span-11 last">&nbsp;<B><?php echo CHtml::encode($model->user_name); ?></B></DIV>

    <DIV class=" span-6"><?php echo CHtml::encode($model->usersData->getAttributeLabel('site')); ?></DIV>
    <DIV class=" span-11 last">&nbsp;<B><?php echo CHtml::encode($model->usersData->site); ?></B></DIV>

    <DIV class=" span-6"><?php echo CHtml::encode($model->usersData->getAttributeLabel('facebook_address')); ?></DIV>
    <DIV class=" span-11 last">&nbsp;<B><?php echo CHtml::encode($model->usersData->facebook_address); ?></B></DIV>

    <DIV class=" span-6"><?php echo CHtml::encode($model->usersData->getAttributeLabel('twitter_address')); ?></DIV>
    <DIV class=" span-11 last">&nbsp;<B><?php echo CHtml::encode($model->usersData->twitter_address); ?></B></DIV>

</div>

<div class="view">
    
    <TABLE width="80%">
        <TR class="odd">
            <TD><?php echo CHtml::encode($model->getAttributeLabel('user_name')); ?></TD>
            <TD width="70%">&nbsp;<B><?php echo CHtml::encode($model->user_name); ?></B></TD>
        </TR>
        <TR class="even">
            <TD class="even"><?php echo CHtml::encode($model->usersData->getAttributeLabel('site')); ?></TD>
            <TD>&nbsp;<B><?php echo CHtml::encode($model->usersData->site); ?></B></TD>
        </TR>
        <TR class="odd">
            <TD><?php echo CHtml::encode($model->usersData->getAttributeLabel('facebook_address')); ?></TD>
            <TD>&nbsp;<B><?php echo CHtml::encode($model->usersData->facebook_address); ?></B></TD>
        </TR>
        <TR class="even">
            <TD><?php echo CHtml::encode($model->usersData->getAttributeLabel('twitter_address')); ?></TD>
            <TD>&nbsp;<B><?php echo CHtml::encode($model->usersData->twitter_address); ?></B></TD>
        </TR>
    </TABLE>
    
</div>
*/ ?>

<div class="view">

    <H2>
        <?php echo CHtml::link(CHtml::encode($model->user_name), array('viewMyPrivateProfile', 'id'=>$model->id)); ?>
    </H2>

    <SECTION>
        <?php echo CHtml::encode($model->usersData->getAttributeLabel('desctiption')); ?>:
        <B><?php echo '<PRE class="small box">' . CHtml::encode($model->usersData->description) . '</PRE>'; ?></B>
        <br />
    </SECTION>
    
    <SECTION>
        <?php echo CHtml::encode($model->usersData->getAttributeLabel('site')); ?>:
        <B><?php echo CHtml::encode($model->usersData->site); ?></B>
        <br />

        <?php echo CHtml::encode($model->usersData->getAttributeLabel('facebook_address')); ?>:
        <B><?php echo CHtml::encode($model->usersData->facebook_address); ?></B>
        <br />

        <?php echo CHtml::encode($model->usersData->getAttributeLabel('twitter_address')); ?>:
        <B><?php echo CHtml::encode($model->usersData->twitter_address); ?></B>
        <br />
    </SECTION>

</div>
