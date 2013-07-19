<?php
/**
 * SiteEmailsContentController class file.
 * Controller class file for updating various email content sent by this applocation.
 *
 * @copyright	Copyright &copy; 2012 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 */
/**
 * SiteEmailsContentController class. Allow to set up the emails content sent by this site.
 * @package		bum
 */

class SiteEmailsContentController extends BumController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
            'ajaxOnly + AJAXUpdate',
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
			array('allow', 
				'actions'=>array('AJAXUpdate'),
                'roles'=>array('settings_emails_customization'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAJAXUpdate($name)
	{
		$model=$this->loadModel($name);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SiteEmailsContent']))
		{
			$model->attributes=$_POST['SiteEmailsContent'];
			if($model->save()){
                echo json_encode(array('message'=>'Email content saved!', 'status'=>'ok'));
            }else{
                echo json_encode(array('message'=>'Email content could not be saved! Maybe application is in demo mode?', 'status'=>'ok'));
            }
		}else{
            $this->renderPartial('_AJAXUpdate',array(
                'model'=>$model,
            ));
        }
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SiteEmailsContent the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($name)
	{
		$model=SiteEmailsContent::model()->findByAttributes(array('name'=>$name));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SiteEmailsContent $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='site-emails-content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
