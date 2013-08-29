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

/* @var $target facebook link target */
/* @var $appId facebook app id */
/* @var $secret facebook secret */
/* @var $redirect_uri the returning url after facebook login */
/* @var $text text to be dispalyed at facebook login button */

class facebook_app extends CWidget
{
    // getAssetsUrl()
    //    return the URL for this widget's assets, performing the publish operation
    //    the first time, and caching the result for subsequent use.
    private $_assetsUrl;
    
    public $target;
    public $appId;
    public $secret;
    public $redirect_uri;
    public $text;
    
    protected $user;


    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
        {
            $file=dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
            $this->_assetsUrl=Yii::app()->getAssetManager()->publish($file);
        }
        
        return $this->_assetsUrl;
    }
    
    public function init()
    {
        //require '../src/facebook.php';
        
        // Create our Application instance
        $facebook = new facebook(array(
          'appId'  => $this->appId,
          'secret' => $this->secret,
        ));

        // Get User ID
        $this->user = $facebook->getUser();
            
        if(!isset($this->target)) $this->target = "_blank";
        if(!isset($this->redirect_uri)) $this->redirect_uri = Yii::app()->createAbsoluteUrl(Yii::app()->user->loginUrl[0]);
        if(!isset($this->text)) $this->text = 'Sign in with <b>Facebook</b>';
            
        if(Yii::app()->user->isGuest){
            $this->render("facebook_app", array('facebook'=>$facebook));
        }else{
            $this->render("facebook_app", array('facebook'=>$facebook));            
        }
    }
    
}