<?php
/**
 * Facebook widget app.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 * This widget displays the facebook login button.
 */


/* @var $this facebook_app widget */
/* @var $facebook facebook instance */


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
    
    $fb_login_url = $facebook->getLoginUrl(array(
        'redirect_uri'=>$this->redirect_uri,
    ));

    ?><a class="btn-auth btn-facebook" href="<?php echo $fb_login_url; ?>" target="<?php echo $this->target; ?>"><?php 
        echo $this->text; 
    ?></a><?php
endif;
