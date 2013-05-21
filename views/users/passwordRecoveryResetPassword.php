<?php
/**
 * Password recovery ask for new password (reset password).
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $modelPasswordRecovery $passwordRecovery */
/* @var $usersModel $Users */
/* @var $form CActiveForm */
?>

<h1>Type Your New Password!</h1>

<DIV class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-recovery-reset-password-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary(array($modelUsers, $modelPasswordRecovery)); ?>
    
    <?php echo $form->hiddenField($modelPasswordRecovery, 'code_inserted'); ?>
    
    <div class="row"><?php 
        echo $form->labelEx($modelUsers,'password'); 
        echo $form->passwordField($modelUsers,'password',array('size'=>45,'maxlength'=>150)); 
        echo $form->error($modelUsers,'password'); 
    ?></div>

    <div class="row"><?php 
        echo $form->labelEx($modelUsers,'password_repeat'); 
        echo $form->passwordField($modelUsers,'password_repeat',array('size'=>45,'maxlength'=>150)); 
        echo $form->error($modelUsers,'password_repeat'); 
    ?></div>

    <div class="row buttons"><?php 
        echo CHtml::submitButton('Reset Your Password');
        ?> <?php
        echo CHtml::link('cancel', Yii::app()->urlManager->baseUrl); 
    ?></div><?php

$this->endWidget(); ?>
</DIV>