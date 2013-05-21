<?php
/**
 * SettingsController class file.
 * Controller class file for table settings.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * SettingsController class. Allow to set up the settings.
 * @package		bum
 */

class SettingsController extends BumController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/bum';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('batchUpdate'),
				'roles'=>array('settings_manage'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * Update settings values
     */
	public function actionBatchUpdate()
    {
        // retrieve items to be updated in a batch mode
        $settings =  Settings::model()->findAll(array('order'=>'setting_order', 'index'=>'id'));
        
        if(isset($_POST['Settings']))
        {
            foreach($settings as $i=>$setting)
            {
                if(isset($_POST['Settings'][$i])){
                    $setting->attributes = $_POST['Settings'][$i];
                }
                if($setting->save()){      
                    BumSettings::checkInitSettings(Yii::app()->getModule('bum'));
                }else{
                    
                }
            }
        }
        // displays the view to collect tabular input
        $this->render('batchUpdate',array('settings'=>$settings));
    }
    
}
