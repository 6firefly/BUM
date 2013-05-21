<?php
/**
 * Password recovery form; find user.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $model FindUserBy */
/* @var $form CActiveForm */
?>

<h1>Reset Your Password</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'email-passwordRecovery-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <fieldset>
        <legend>Email or User Name:</legend>
        
        <?php if (Yii::app()->user->isGuest): ?>
            <div class="row">
                <?php echo $form->labelEx($model,'email_or_user_name'); ?>
                <?php echo $form->textField($model,'email_or_user_name',array('size'=>60,'maxlength'=>60)); ?>
                <?php echo $form->error($model,'email_or_user_name'); ?>
            </div>
        <?php else: ?>
            <div class="row">
                <?php echo $form->labelEx($model,'email_or_user_name'); ?>
                <?php echo $form->textField($model,'email_or_user_name',array('size'=>60,'maxlength'=>60, 'readonly'=>'readonly', 'value'=>Yii::app()->user->primaryEmail)); ?>
                <?php echo $form->error($model,'email_or_user_name'); ?>
                <DIV>If this is not the correct email, please change your primary email! Go to <?php echo CHtml::link('Update Profile Information', array('users/update', 'id'=>Yii::app()->user->id) ); ?> page.</DIV>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend>Are you human?</legend>
        <?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <?php echo $form->labelEx($model,'verifyCode'); ?>
            <div>
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model,'verifyCode'); ?>
            </div>
            <div class="hint">Please enter the letters as they are shown in the image above.
            <br/>Letters are not case-sensitive.</div>
            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
        <?php endif; ?>
    </fieldset>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->