<?php
/**
 * Sign Up form; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-singUp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php 
    // if site is not invitation based, then do not display invitation code errors... :)
    if(Yii::app()->getModule('bum')->invitationBasedSignUp):
        echo $form->errorSummary(array($model, $model->invitations)); 
    else:
        echo $form->errorSummary(array($model)); 
    endif;
    
    ?><fieldset>
        <legend>Username and password:</legend>
        <div class="row">
            <?php echo $form->labelEx($model,'user_name'); ?>
            <?php if($model->isNewRecord): ?>
                <?php echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45)); ?>
            <?php else: ?>
                <?php echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45, 'readonly'=>'readonly', 'disabled'=>true)); ?>
            <?php endif; ?>
            <?php echo $form->error($model,'user_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('size'=>45,'maxlength'=>150)); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'password_repeat'); ?>
            <?php echo $form->passwordField($model,'password_repeat',array('size'=>45,'maxlength'=>150)); ?>
            <?php echo $form->error($model,'password_repeat'); ?>
        </div>
    </fieldset>    

    <fieldset>
        <legend>Email address is required in order to activate your account:</legend>
        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
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
    
    <?php if(Yii::app()->getModule('bum')->invitationBasedSignUp): ?>
        <fieldset>
            <legend>Invitation code?</legend>
            <?php echo $form->labelEx($model->invitations,'invitation_code'); ?>
            <?php echo $form->textField($model->invitations,'invitation_code',array('size'=>10,'maxlength'=>10)); ?>
            <?php echo $form->error($model->invitations,'invitation_code'); ?>
        </fieldset>
    <?php else:
            // if site is not invitation based, then do not display invitation_code field... :)
            echo $form->hiddenField($model->invitations,'invitation_code');
    endif; ?>
    
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
    
	<div class="row buttons">
        <?php echo CHtml::link('Resend Confirmation Email', array('users/resendSignUpConfirmationEmail')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->