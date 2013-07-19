<?php
/**
 * Sign up confirmation email view file; partial view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * When a user register, this confirmation/activation email is sent to that user.
 */

/* @var $modelUsersData model UsersData */
/* @var $moduleSiteEmailsContact module SiteEmailsContact */

echo eval($moduleSiteEmailsContact->body);
