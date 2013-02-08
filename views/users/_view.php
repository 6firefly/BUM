<?php
/**
 * View information about a user; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */

/* @var $this UsersController */
/* @var $data Users */
?>

<div class="view">

    <H2>
        <?php echo CHtml::link(CHtml::encode($data->user_name), array('viewMyPrivateProfile', 'id'=>$data->id)); ?>
    
        <SMALL>
            (<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
            <?php echo CHtml::link(CHtml::encode($data->id), array('viewMyPrivateProfile', 'id'=>$data->id)); ?>)
        </SMALL>
    </H2>

    <H3>
        <?php echo CHtml::encode($data->name); ?>
        <?php echo CHtml::encode($data->surname); ?>
	</H3>

    <H4>
        <?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:
        <b><?php echo CHtml::encode($data->getActiveText()); ?></b><BR/>
        <?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:
        <b><?php echo CHtml::encode($data->getStatusText()); ?></b>
	</H4>
        
    <SECTION>
        <?php if((Yii::app()->user->id === $data->id) || Yii::app()->user->checkAccess('emails_all_view')): ?>
            <H5>
                <?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:
                <B><?php echo CHtml::encode($data->email); ?></B><?php

                $myEmails=BumUserEmails::findMyEmails($data->id);
                if($myEmails->itemCount>0):
                    ?><SMALL><DIV>Secondary email(s):<?php 


                        echo $this->renderPartial('/emails/_viewMyEmails', array(
                           'myEmails'=>$myEmails,
                    )); ?></DIV></SMALL><?php
                endif;
            ?></H5>
        <?php endif; ?>
    </SECTION>

    <SECTION>
        <?php echo CHtml::encode($data->usersData->getAttributeLabel('desctiption')); ?>:
        <B><?php echo '<PRE class="small box">' . CHtml::encode($data->usersData->description) . '</PRE>'; ?></B>
        <br />
    </SECTION>
    
    <SECTION>
        <?php echo CHtml::encode($data->getAttributeLabel('date_of_last_access')); ?>:
        <B><?php echo CHtml::encode($data->date_of_last_access); ?></B>
        <br />

        <?php echo CHtml::encode($data->getAttributeLabel('date_of_password_last_change')); ?>:
        <B><?php echo CHtml::encode($data->date_of_password_last_change); ?></B>
        <br />

        <?php echo CHtml::encode($data->getAttributeLabel('date_of_creation')); ?>:
        <B><?php echo CHtml::encode($data->date_of_creation); ?></B>
        <br />
    </SECTION>

    <SECTION>
        <?php echo CHtml::encode($data->usersData->getAttributeLabel('site')); ?>:
        <B><?php echo CHtml::encode($data->usersData->site); ?></B>
        <br />

        <?php echo CHtml::encode($data->usersData->getAttributeLabel('facebook_address')); ?>:
        <B><?php echo CHtml::encode($data->usersData->facebook_address); ?></B>
        <br />

        <?php echo CHtml::encode($data->usersData->getAttributeLabel('twitter_address')); ?>:
        <B><?php echo CHtml::encode($data->usersData->twitter_address); ?></B>
        <br />
    </SECTION>

    <?php if( ( Yii::app()->user->checkAccess('users_all_view')) ): ?>
        <SECTION>
            <?php echo CHtml::encode($data->usersData->getAttributeLabel('obs')); ?>:
            <B><?php echo '<PRE class="small box">' . CHtml::encode($data->usersData->obs) . '</PRE>'; ?></B>
            <br />
        </SECTION>
    <?php endif; ?>
        
</div>