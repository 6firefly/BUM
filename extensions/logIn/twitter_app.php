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

/* @var $target twitter link target */
/* @var $key twitter customer id */
/* @var $secret twitter customer secret */
/* @var $redirect_uri the returning url after twitter login */
/* @var $text text to be dispalyed at twitter login button */

class twitter_app extends CWidget
{
    // getAssetsUrl()
    //    return the URL for this widget's assets, performing the publish operation
    //    the first time, and caching the result for subsequent use.
    private $_assetsUrl;
    
    public $target;
    public $key;
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
            
        if(!isset($this->target)) $this->target = "_blank";
        if(!isset($this->redirect_uri)) $this->redirect_uri = Yii::app()->createAbsoluteUrl(Yii::app()->user->loginUrl[0]);
        if(!isset($this->text)) $this->text = 'Sign in with <b>Twitter</b>';
            
        if(Yii::app()->user->isGuest){
            $this->render("twitter_app");
        }else{
            $this->render("twitter_app");            
        }
    }
    
}