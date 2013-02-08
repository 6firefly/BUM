<?php 
/**
 * View my emails view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/* @var myEmails CActiveDataProvider('Emails', ...); or false if user don't has the right to access this view */

if ($myEmails) {
    ?><DIV style="padding-left: 10px;"><?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$myEmails,
        'template'=>'{items}', // no summary text and no pages
        'emptyText'=>'',
        'itemView'=>'/emails/_viewMyEmail',
    ));
    ?></DIV><?php
}
