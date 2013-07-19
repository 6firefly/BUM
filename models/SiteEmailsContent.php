<?php
/**
 * SiteEmailsContent class file.
 * Model class file for table site_emails_content.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 *
 * SiteEmailsContent  class.
 * @package		bum
 * 
 * This is the model class for table "site_emails_content".
 *
 * The followings are the available columns in table 'site_emails_content':
 * @property string $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property string $available_variables
 * @property string $date_of_update
 * @property integer $version
 *
 * The followings are the available model relations:
 * @property Settings $name0
 */
class SiteEmailsContent extends BumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SiteEmailsContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'site_emails_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, subject, body', 'required'),
			array('version', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('subject', 'length', 'max'=>100),
			array('available_variables, date_of_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, subject, body, available_variables, date_of_update, version', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'name0' => array(self::BELONGS_TO, 'Settings', 'name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'subject' => 'Subject',
			'body' => 'Body',
			'available_variables' => 'Available Variables',
			'date_of_update' => 'Date Of Update',
			'version' => 'Version',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('available_variables',$this->available_variables,true);
		$criteria->compare('date_of_update',$this->date_of_update,true);
		$criteria->compare('version',$this->version);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    
    /**
     * Update some datatime statistical fields.
     */
    public function beforeSave() {
        if(!Yii::app()->getModule('bum')->db_triggers){
            if(!$this->isNewRecord){
                $this->date_of_update = new CDbExpression('NOW()');
            }
        }
        return parent::beforeSave();
    }        
}