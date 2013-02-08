<?php
/**
 * Add a new email to my emails; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this EmailsController or UsersController */
/* @var $model Emails */
/* @var $modelUsers modelUsers */
/* @var $form CActiveForm */

?><div class="form"><?php 

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'emails-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true)
    )); 

        echo $form->hiddenField($model,'id_user',array('value'=>$modelUsers->id)); 

        ?><div class="row"><?php 
            echo $form->labelEx($model,'name');
            echo $form->textField($model,'name',array('size'=>60,'maxlength'=>60));
            echo $form->error($model,'name');
        ?></div>

        <div class="row buttons"><?php 

            echo CHtml::ajaxSubmitButton(
                          'Add', 
                          array('emails/create', 'id_user'=>$modelUsers->id), 
                          array(
                              //'update'=>'#addEmail', // is ignoder if success function is present...
                              'success'=>'function(data){
                                        $("#addEmail").html(data);
                                        $.ajax({
                                          url:"' . Yii::app()->createUrl($this->module->name . '/emails/viewMyEmails', array('id_user'=>$modelUsers->id)) . '",
                                          success:function(response){
                                              $("#printEmails").html(response);
                                          }});
                                        $.ajax({
                                          url:"' . Yii::app()->createUrl($this->module->name . '/emails/hasUnverifiedEmails', array('id_user'=>$modelUsers->id)) . '",
                                          success:function(response){
                                              var hasUnverifiedEmails = jQuery.parseJSON(response);
                                              if (hasUnverifiedEmails) {
                                                  $("#CreateNewEmailButton").addClass("hide");
                                              }else{
                                                  $("#CreateNewEmailButton").removeClass("hide");
                                              }
                                          }});
                                  }'
                          ),
                          array(
                              'id' => 'ajaxButtonAddNewEmail',
                              'name' => 'ajaxButtonAddNewEmail',
                              'live'=>false
                          )
                       ); 
            echo CHtml::button('Cancel', array('onClick'=>'js:$("#addEmail").html("");')); 
        ?></div><?php 

    $this->endWidget(); 

?></div><!-- form --><?php
