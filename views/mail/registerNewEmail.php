<?php
/**
 * Email verification view file; partial view.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user register a new email address, this verification email is sent in order to confirm newly added email.
 */

/* @var $modelEmails model Emails */
/* @var $moduleSiteEmailsContact module SiteEmailsContact */

echo eval($moduleSiteEmailsContact->body);
            