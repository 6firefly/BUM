<?php
/**
 * Print the invited emails and status..
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This form file is used to print email and status for invitations sent.
 */

/* @var $data Invitations -> dataprovider for curent user */

?><DIV class="column span-10 small bottom "><?php echo $data->email; ?></DIV>
<DIV class="span-5 last small bottom "><?php echo isset($data->date_of_invitation_accepted)?"Accepted":""; ?></DIV>