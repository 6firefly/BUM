<?php

class twitterRedirect extends CAction{
    public function run(){        

        spl_autoload_unregister(array('YiiBase', 'autoload')); // Disable Yii autoloader
        require_once(dirname(__FILE__) . '/../../components/twitteroauth-master/twitteroauth/twitteroauth.php');
        /* Build TwitterOAuth object with client credentials. */
        $connection = new TwitterOAuth(Yii::app()->getModule('bum')->twitter_key, Yii::app()->getModule('bum')->twitter_secret);
        spl_autoload_register(array('YiiBase', 'autoload')); // Register Yii autoloader

        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken(Yii::app()->createAbsoluteUrl(Yii::app()->user->loginUrl[0], array('social'=>'twitter')));

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
          case 200:
            /* Build authorize URL and redirect user to Twitter. */
            $url = $connection->getAuthorizeURL($token);
            header('Location: ' . $url); 
            break;
          default:
            Yii::app()->user->setFlash('error', "Could not connect to Twitter. Refresh the page or try again later!");
            $this->controller->redirect(array("users/login"));
        }
        
    }
}