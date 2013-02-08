<?php
/**
 * Send invitations; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This form file is used to send invitations to friends.
 */

/* @var $this InvitationsController */
/* @var $model Invitations */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invitations-form',
	'enableAjaxValidation'=>true,
    'htmlOptions'=>array('onSubmit'=>'return false;'), // deactivate the default submit action
    'enableClientValidation'=>true,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<fieldset>

        <?php echo $form->hiddenField($model, 'id_user'); ?>
        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'note'); ?>
            <?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'note'); ?>
        </div>
        
	</fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->