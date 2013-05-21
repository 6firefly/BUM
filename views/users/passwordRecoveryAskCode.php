<?php
/**
 * Password recovery ask for email code.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $modelPasswordRecovery $passwordRecovery */
/* @var $form CActiveForm */
?>

<h1>Check Your Email!</h1>

<DIV class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-recovery-form',
	'enableAjaxValidation'=>false,
)); ?>
    <DIV class="note flash-notice">An email with a validation code was sent to you. Enter it below to continue to reset your password.</DIV>

	<?php echo $form->errorSummary($modelPasswordRecovery); ?>
    <div class="row">
        <?php echo $form->labelEx($modelPasswordRecovery,'code_inserted'); ?>
        <?php echo $form->textField($modelPasswordRecovery,'code_inserted',array('size'=>13,'maxlength'=>10)); ?>
        <?php echo $form->error($modelPasswordRecovery,'code_inserted'); ?>
    </div>

    <div class="row buttons"><?php 
        echo CHtml::submitButton('Continue');
        ?> <?php
        echo CHtml::link('cancel', Yii::app()->urlManager->baseUrl); 
    ?></div><?php

$this->endWidget(); ?>
</DIV>