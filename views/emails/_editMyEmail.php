<?php
/**
 * Edit my email; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $data Emails */
?><div><?php 
    
    echo CHtml::encode($data->name);
    
    if(!$data->verified) : 
        // resend verification email;
        ?> <SPAN id="validationLink_id<?php echo $data->id; ?>"><?php
            echo CHtml::AjaxLink('resend verification link', array("emails/resendVerificationLink", 'id'=>$data->id), 
                     array('update'=>'#validationLink_id' . $data->id),
                     array('id'=>'ResendValidationEmail'.$data->id, 'name'=>'ResendValidationEmail'.$data->id, 'live'=>false)
                 ); 
        ?></SPAN> <?php
    endif;
        
    ?> <SPAN><?php
        echo CHtml::AjaxLink('delete email', array("emails/delete", 'id'=>$data->id), array(
                      //'update'=>'#printEmails',
                      'success'=>'function(data){
                                $("#printEmails").html(data);
                                $.ajax({
                                  url:"' . Yii::app()->createUrl($this->module->name . '/emails/hasUnverifiedEmails', array('id_user'=>$data->id_user)) . '",
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
                 array('id'=>'DeleteEmail'.$data->id, 'name'=>'DeleteEmail'.$data->id, 'live'=>false)
             ); 
    ?></SPAN> <?php
    
    if($data->verified) :
        ?> <SPAN><?php
            echo CHtml::AjaxLink('make primary', array("emails/makePrimary", 'id'=>$data->id), 
                     array('success'=>'function(response){
                            var primaryEmail = jQuery.parseJSON(response);
                            if (primaryEmail.changed) {
                                $("#Users_email").val(primaryEmail.name);
                                $.ajax({
                                  url:"' . Yii::app()->createUrl($this->module->name . '/emails/viewMyEmails', array('id_user'=>$data->id_user)) . '",
                                  success:function(response){
                                      $("#printEmails").html(response);
                                  }});
                                alert(primaryEmail.message);
                            } else {
                                alert(primaryEmail.message);
                            }
                         }'),
                     array('id'=>'makePrimary_id'.$data->id, 'name'=>'makePrimary_id'.$data->id, 'live'=>false)
                 ); 
        ?></SPAN> <?php
    endif;
    
?></div><?php
