<?php
/**
 * View/set various emails send by this application.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 */

/* @var $this SiteEmailsContentController */
/* @var $model SiteEmailsContent */
/* @var $form CActiveForm */
?><div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-emails-content-form',
	'enableAjaxValidation'=>true,
    'htmlOptions'=>array('onSubmit'=>'return false;'), // deactivate the default submit action
    'enableClientValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model, 'id'); ?>
    <?php echo $form->hiddenField($model, 'name'); ?>
    
	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>62,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>9, 'cols'=>103, 'wrap'=>'off')); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model,'available_variables'); ?>
        <DIV  id="code"><small><I><?php
        echo $model->available_variables;
	?></I></small></DIV></div>

<?php $this->endWidget(); ?>

</div><!-- form -->