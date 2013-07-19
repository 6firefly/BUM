<?php
/**
 * Email invitation view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user invites another person to this site, this email is sent to the respective.
 */

/* @var $model Invitations */
/* @var $moduleSiteEmailsContact module SiteEmailsContact */

echo eval($moduleSiteEmailsContact->body);