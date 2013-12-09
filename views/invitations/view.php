<?php
/**
 * Manage all users view file.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 *  
 * Menu links are displayed only if the user has the right to see those pages.
 */

/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Invitations',
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
    
	array('template'=>'<HR style="margin:0 auto;"/>', 'visible'=>(Yii::app()->user->checkAccess('users_all_privateProfile_view') && !Yii::app()->user->isGuest)), // separator
	array('label'=>'See Sent Invites', 'url'=>array('invitations/view'), 'visible'=>(Yii::app()->user->checkAccess('users_all_privateProfile_view') && !Yii::app()->user->isGuest)),
    
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

?>

<h1>See Invitations Sent</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p><?php 

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('invitations-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); 
?><div class="search-form" style="display:none"><?php 
$this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form --><?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'invitations-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    //'afterAjaxUpdate' => 'function(){}',
	'columns'=>array(
		array(
            'class'=>'CLinkColumn',
            'header'=>'User',
            'urlExpression'=>'isset($data->idUser)?Yii::app()->createUrl("' . $this->getModule()->name . '/users/viewProfile",array("id"=>$data->idUser->id)):""',
            'labelExpression'=>'isset($data->idUser)?$data->idUser->user_name:""',
        ), // what user sent the invitation
		array(
            'class'=>'CLinkColumn',
            'header'=>'Invited User',
            'urlExpression'=>'isset($data->idUserInvited)?Yii::app()->createUrl("' . $this->getModule()->name . '/users/viewProfile",array("id"=>$data->idUserInvited->id)):""',
            'labelExpression'=>'isset($data->idUserInvited)?$data->idUserInvited->user_name:""',
        ), // what user accepted the invitation
		'email', // to what email was the invitation sent
        'note', // invitation note
        'date_of_invitation_send', // date when the invitation was sent
        'date_of_invitation_accepted', // date when the invitation was accepted
	),
)); 
