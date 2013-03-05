<?php
/**
 * Search form for searching a user; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
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
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_name'); ?>
		<?php echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
        <?php echo $form->dropDownList($model,'active', array(""=>"") + Users::getActiveOptions()); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', array(""=>"") + Users::getStatusOptions()); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'surname'); ?>
		<?php echo $form->textField($model,'surname',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<?php
    /* 
    <div class="row">
		<?php echo $form->label($model,'date_of_last_access'); ?>
		<?php echo $form->textField($model,'date_of_last_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_of_password_last_change'); ?>
		<?php echo $form->textField($model,'date_of_password_last_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_of_creation'); ?>
		<?php echo $form->textField($model,'date_of_creation'); ?>
	</div>
     
    */ ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->