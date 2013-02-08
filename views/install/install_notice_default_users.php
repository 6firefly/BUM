<?php
/**
 * Install default users view.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License
 *  
 */

/* @var $defaultUsers array() */

foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
} 

if(count($defaultUsers) > 0){
    echo "The following users were created:";
}
?><UL><?php
    foreach($defaultUsers as $defaultUser):
        ?><LI><?php
            ?>user name: <B><?php echo $defaultUser['user_name']; ?></B>;<BR/><?php
            ?>email: <B><?php echo $defaultUser['email']; ?></B>;<BR/><?php
            ?>password: <?php echo $defaultUser['pass']; ?>;<BR/><?php
        ?></LI><?php
    endforeach;
?></UL><?php
