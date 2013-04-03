<?php
/**
 * Create a new user; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This form file is used for several scenario cases, like: create (a new user by the admin), signUp, update.
 */

/* @var $this UsersController */
/* @var $model Users */
/* @var $modelUsersData modelUsersData */
/* @var myEmails $myEmails CActiveDataProvider('Emails', ... ) */
/* @var hasUnverifiedEmails $hasUnverifiedEmails = true if user has unverified emails or false otherwise */
/* @var $form CActiveForm */

?><div class="form"><?php 

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'enableAjaxValidation'=>false,
    )); 

        ?><p class="note">Fields with <span class="required">*</span> are required.</p><?php 

        echo $form->errorSummary(array($model, $modelUsersData)); 

        ?><fieldset>
            <legend>Username and Email:</legend>
            <div class="row"><?php 

                echo $form->labelEx($model,'user_name'); 

                if($model->scenario == 'create' || $model->scenario == 'signUp'): 
                    // only allow this field to be edited if it's in create or signUp scenario...
                    echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45));
                else:
                    // primary email can be changed; just set as primary on of other emails associated with this user
                    echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45, 'readonly'=>'readonly', 'disabled'=>true));
                endif;

                echo $form->error($model,'user_name'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($model,'email');

                if($model->scenario == 'create' || $model->scenario == 'signUp'): 
                    ?><DIV class="span-10"><?php 
                        echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); 
                    ?></DIV><?php 
                else:
                    if((Yii::app()->user->id === $model->id) || Yii::app()->user->checkAccess('emails_all_view')): 
                        ?><DIV class="span-10"><?php 
                            echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60, 'readonly'=>'readonly', 'disabled'=>true)); 
                        ?></DIV>
                        <DIV class="span-5 last"><?php 

                            if($model->scenario == 'update' && $myEmails):
                                echo CHtml::AjaxLink('new email address', array("emails/create", 'id_user'=>$model->id), array('update'=>'#addEmail'), array('class'=>'displayInline' . (($hasUnverifiedEmails)?' hide':''), 'id'=>'CreateNewEmailButton', 'live'=>false));
                            endif;
                        ?></DIV><?php
                    endif;

                endif;
                
                echo $form->error($model,'email', array('class'=>'errorMessage span-15 last')); 

            ?></div><?php 

            if($model->scenario == 'create' || $model->scenario == 'signUp'): 
            else: 
                ?><DIV id="emails" class="span-15 last">
                    <DIV id="addEmail"></DIV>
                    <DIV id="printEmails" class="span-15 last"><?php 
                        echo $this->renderPartial('/emails/_editMyEmails', array(
                           'myEmails'=>$myEmails,
                        ));
                    ?></DIV>
                </DIV><?php 
            endif; 

        ?></fieldset>

        <fieldset>
            <legend>Password and access:</legend><?php 

            if($model->scenario == 'update'): 
                
                // If the user has the password_change right (operation) then old password is not needed anymore
                if(!Yii::app()->user->checkAccess("password_change")):
                    // request for the old password only on the update scenario
                    ?><div class="row"><?php 
                        echo $form->labelEx($model,'password_old'); 
                        echo $form->passwordField($model,'password_old',array('size'=>45,'maxlength'=>150)); 
                        echo $form->error($model,'password_old'); 
                    ?></div><?php 
                endif; 
            endif; 

            ?><div class="row"><?php 
                echo $form->labelEx($model,'password'); 
                echo $form->passwordField($model,'password',array('size'=>45,'maxlength'=>150)); 
                echo $form->error($model,'password'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($model,'password_repeat'); 
                echo $form->passwordField($model,'password_repeat',array('size'=>45,'maxlength'=>150)); 
                echo $form->error($model,'password_repeat'); 
            ?></div><?php 

            if( ( Yii::app()->user->checkAccess('users_all_view')) ):
                ?><div class="row"><?php 
                    echo $form->labelEx($model,'active'); 
                    echo $form->dropDownList($model,'active', Users::getActiveOptions()); 
                    echo $form->error($model,'active'); 
                ?></div><?php 
                
                ?><div class="row"><?php 
                    echo $form->labelEx($model,'status'); 
                    echo $form->dropDownList($model,'status', Users::getStatusOptions()); 
                    echo $form->error($model,'status'); 
                ?></div><?php 
            endif; 

        ?></fieldset>

        <fieldset>
            <legend>About you:</legend>
            <div class="row"><?php 
                echo $form->labelEx($model,'name'); 
                echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); 
                echo $form->error($model,'name'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($model,'surname'); 
                echo $form->textField($model,'surname',array('size'=>45,'maxlength'=>45)); 
                echo $form->error($model,'surname'); 
            ?></div><?php

            /* UsersData model fields */
            ?><div class="row"><?php 
                echo $form->labelEx($modelUsersData,'description'); 
                echo $form->textArea($modelUsersData,'description',array('rows'=>6, 'cols'=>50)); 
                echo $form->error($modelUsersData,'description'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($modelUsersData,'site');
                echo $form->textField($modelUsersData,'site',array('size'=>60,'maxlength'=>1500));
                echo $form->error($modelUsersData,'site'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($modelUsersData,'facebook_address'); 
                echo $form->textField($modelUsersData,'facebook_address',array('size'=>60,'maxlength'=>60)); 
                echo $form->error($modelUsersData,'facebook_address'); 
            ?></div>

            <div class="row"><?php 
                echo $form->labelEx($modelUsersData,'twitter_address'); 
                echo $form->textField($modelUsersData,'twitter_address',array('size'=>60,'maxlength'=>60)); 
                echo $form->error($modelUsersData,'twitter_address'); 
            ?></div>
        </fieldset>
        

        <?php if( ( Yii::app()->user->checkAccess('users_all_view')) ): ?>
            <fieldset>
                <legend>User things:</legend><?php
                
                if(Yii::app()->getModule('bum')->invitationBasedSignUp || Yii::app()->getModule('bum')->invitationButtonDisplay):
                    ?><div class="row"><?php 
                        echo $form->labelEx($modelUsersData,'invitations_left'); 
                        echo $form->textField($modelUsersData,'invitations_left',array('maxlength'=>5)); 
                        echo $form->error($modelUsersData,'invitations_left'); 
                    ?></div><?php
                else:
                        echo $form->hiddenField($modelUsersData,'invitations_left'); 
                endif;
                
                ?><div class="row"><?php 
                    echo $form->labelEx($modelUsersData,'obs'); 
                    echo $form->textArea($modelUsersData,'obs',array('rows'=>6, 'cols'=>50)); 
                    echo $form->error($modelUsersData,'obs'); 
                ?></div>
            </fieldset>
        <?php endif; ?>

        <div class="row buttons"><?php 
            echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); 
        ?></div><?php

    $this->endWidget(); 

?></div><!-- form --><?php
