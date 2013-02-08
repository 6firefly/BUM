<?php
/**
 * Show to whom invitation email has been sent; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This form file is used to print email and status for invitations sent
 */

/* @var $this InvitationController */
/* @var $dataProvider Invitations -> for curent user */

?><H6>Invitations sent to:</H6>
<DIV style="height:150px; border-bottom:1px solid; border-color:#999; margin-bottom:5px; padding-bottom:5px; position: relative;"><?php $this->widget('zii.widgets.CListView', array(
    'id'=>'invitationsSentList',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'htmlOptions'=>array('class'=>'container', 'style'=>'width:auto; position: absolute; bottom: 10px;'),
    'summaryText'=>'',
    'pagerCssClass'=>'left',
)); ?></DIV>
