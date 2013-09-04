<?php
/**
 * Twitter widget app.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This widget displays the twitter login button.
 */


/* @var $this twitter_app widget */


$cs = Yii::app()->getClientScript();
$cs->registerCssFile($this->assetsUrl . '/auth-buttons.css');

/**
 * 
 *  http://nicolasgallagher.com/lab/css3-social-signin-buttons/
 * 
 */

// Login or logout url will be needed depending on current user state.
if ($this->user) :
    /* ?><a class="btn-auth btn-facebook" href="<?php echo $facebook->getLogoutUrl();?>" target="<?php echo $this->target; ?>">
        <b>Facebook</b> LogOut
    </a><?php // */
else :
    
    ?><a class="btn-auth btn-twitter" href="<?php echo Yii::app()->createAbsoluteUrl('bum/users/twitterRedirect'); ?>" target="<?php echo $this->target; ?>"><?php 
        echo $this->text; 
    ?></a><?php
endif;
