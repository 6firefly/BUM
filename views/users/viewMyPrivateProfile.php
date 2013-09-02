<?php
/**
 * View my private profile.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 *  
 * Menu links are displayed only if the user has the right to see those pages.
 */

/* @var $this UsersController */
/* @var $model Users */
/* @var myEmails $myEmails CActiveDataProvider('Emails', ... ) */

$this->breadcrumbs=array(
	'Users'=>(Yii::app()->user->checkAccess("users_admin")?array('admin'):""),
	Yii::app()->user->name,
    'Private Profile',
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

        array('label'=>'Update Profile Information', 'url'=>array('users/update', 'id'=>$model->id), 'visible'=>(((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('users_profile_update')) && !in_array(Yii::app()->user->status, Users::getSocialOnlyStatuses())), 'active'=>true),
        array('label'=>'Update Profile Information', 'url'=>array('users/socialUpdate', 'id'=>$model->id), 'visible'=>(((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('users_profile_update')) && in_array(Yii::app()->user->status, Users::getSocialOnlyStatuses())), 'active'=>true),
        
        array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>Yii::app()->user->checkAccess("users_delete")),
    ), 'submenuOptions'=>array('style'=>'padding:0 0 0 15px;')),
    
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

?>

<h1>View Private Data for User #<?php echo $model->user_name; ?></h1>

<div class="view">
    
    <?php if (!in_array($model->status, Users::getSocialOnlyStatuses())): ?>
        <H2><?php 
            echo CHtml::link(CHtml::encode($model->user_name), array('viewMyPrivateProfile', 'id'=>$model->id)); 
            
            echo " &nbsp; ";
            if(is_array($model->social_login) && array_intersect($model->social_login, Users::getSocialLogIn())): 
                echo CHtml::image(Yii::app()->getModule("bum")->assetsUrl . "/images/facebook_small.gif","f",array("title"=>"facebook logIn is enabled", "style"=>"width:11px;height:11px;"));  
            endif;
        ?></H2>
    <?php endif; ?>
        

    <H3>
        <?php echo CHtml::encode($model->name); ?>
        <?php echo CHtml::encode($model->surname); ?>
	</H3>

    <H4>
        <?php if( ( Yii::app()->user->checkAccess('users_all_view')) ): ?>
            <?php echo CHtml::encode($model->getAttributeLabel('active')); ?>:
            <b><?php echo CHtml::encode($model->getActiveText()); ?></b><BR/>
            <?php echo CHtml::encode($model->getAttributeLabel('status')); ?>:
            <b><?php echo CHtml::encode($model->getStatusText()); ?></b>
        <?php endif; ?>
	</H4>
        
    <?php if (!in_array($model->status, Users::getSocialOnlyStatuses())): ?>
        <SECTION>
            <?php if((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('emails_all_view')): ?>
                <H5>
                    <?php echo CHtml::encode($model->getAttributeLabel('email')); ?>:
                    <B><?php echo CHtml::encode($model->email); ?></B><?php

                    $myEmails=BumUserEmails::findMyEmails($model->id);
                    if($myEmails->itemCount>0):
                        ?><SMALL><DIV>Secondary email(s):<?php 


                            echo $this->renderPartial('/emails/_viewMyEmails', array(
                               'myEmails'=>$myEmails,
                        )); ?></DIV></SMALL><?php
                    endif;
                ?></H5>
            <?php endif; ?>
        </SECTION>
    <?php endif; ?>

    <SECTION>
        <?php echo CHtml::encode($model->usersData->getAttributeLabel('desctiption')); ?>:
        <B><?php echo '<PRE class="small box">' . CHtml::encode($model->usersData->description) . '</PRE>'; ?></B>
        <br />
    </SECTION>
    
    <SECTION>
        <?php echo CHtml::encode($model->getAttributeLabel('date_of_last_access')); ?>:
        <B><?php echo CHtml::encode($model->date_of_last_access); ?></B>
        <br />

        <?php echo CHtml::encode($model->getAttributeLabel('date_of_password_last_change')); ?>:
        <B><?php echo CHtml::encode($model->date_of_password_last_change); ?></B>
        <br />

        <?php echo CHtml::encode($model->getAttributeLabel('date_of_creation')); ?>:
        <B><?php echo CHtml::encode($model->date_of_creation); ?></B>
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

    <?php if( ( Yii::app()->user->checkAccess('users_all_view')) ): ?>
        <SECTION>
            <?php echo CHtml::encode($model->usersData->getAttributeLabel('obs')); ?>:
            <B><?php echo '<PRE class="small box">' . CHtml::encode($model->usersData->obs) . '</PRE>'; ?></B>
            <br />
        </SECTION>
    <?php endif; ?>
    
</div>