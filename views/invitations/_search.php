<?php
/**
 * Search form for searching a user; partial view.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */

?><div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'search_user'); ?>
		<?php echo $form->textField($model,'search_user'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'search_user_invited'); ?>
		<?php echo $form->textField($model,'search_user_invited'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_of_invitation_send'); ?>
		<?php echo $form->textField($model,'date_of_invitation_send',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_of_invitation_accepted'); ?>
		<?php echo $form->textField($model,'date_of_invitation_accepted',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->