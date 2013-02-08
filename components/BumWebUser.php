<?php
/**
 * BumWebUser class file.
 *
 * Credits: http://www.yiiframework.com/wiki/60/
 * 
 * @package		bum
 */
/**
 * BumWebUser extends CWebUser class.
 * @package		bum
 * 
 * BumWebUser allows for some user property to be accessed application wide.
 * 
 */
class BumWebUser extends CWebUser {

    // Store the model in order not to repeat the query.
    private $_model;

    // Return user's status.
    // access it by Yii::app()->user->status
    function getStatus(){
      $user = $this->loadUser(Yii::app()->user->id);
      return $user->status;
    }

    // Return user's primary email.
    // access it by Yii::app()->user->primaryEmail
    function getPrimaryEmail(){
      $user = $this->loadUser(Yii::app()->user->id);
      return $user->email;
    }

    // Return user's active state.
    // access it by Yii::app()->user->active
    function getActive(){
      $user = $this->loadUser(Yii::app()->user->id);
      return $user->active;
    }

    /**
    * @return string the status text display for the current issue
    // access it by Yii::app()->user->statusText
    */
    public function getStatusText()
    {
        $user = $this->loadUser(Yii::app()->user->id);
        $statusOptions=$user->statusOptions;
        return isset($statusOptions[$user->status]) ? $statusOptions[$user->status] : "unknown status: ({$user->status})";
    }    
  
  // Load user model.
  protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=Users::model()->findByPk($id);
        }
        return $this->_model;
    }
}
