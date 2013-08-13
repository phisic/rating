<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $ip_address
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $activation_code
 * @property string $forgotten_password_code
 * @property string $forgotten_password_time
 * @property string $remember_code
 * @property string $created_on
 * @property string $last_login
 * @property integer $active
 * @property string $first_name
 * @property string $last_name
 * @property string $company
 * @property string $phone
 * @property string $question
 * @property string $answer
 * @property string $country
 * @property string $is_finished
 * @property string $role
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip_address, username, password, email, created_on, question, country', 'required'),
			array('active', 'numerical', 'integerOnly'=>true),
			array('ip_address', 'length', 'max'=>10),
			array('username, email, company, country', 'length', 'max'=>100),
			array('password, salt, activation_code, forgotten_password_code, remember_code', 'length', 'max'=>40),
			array('forgotten_password_time, created_on, last_login', 'length', 'max'=>11),
			array('first_name, last_name', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>20),
			array('question, answer', 'length', 'max'=>255),
			array('is_finished', 'length', 'max'=>1),
			array('role', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip_address, username, password, salt, email, activation_code, forgotten_password_code, forgotten_password_time, remember_code, created_on, last_login, active, first_name, last_name, company, phone, question, answer, country, is_finished, role', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ip_address' => 'Ip Address',
			'username' => 'Username',
			'password' => 'Password',
			'salt' => 'Salt',
			'email' => 'Email',
			'activation_code' => 'Activation Code',
			'forgotten_password_code' => 'Forgotten Password Code',
			'forgotten_password_time' => 'Forgotten Password Time',
			'remember_code' => 'Remember Code',
			'created_on' => 'Created On',
			'last_login' => 'Last Login',
			'active' => 'Active',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'company' => 'Company',
			'phone' => 'Phone',
			'question' => 'Question',
			'answer' => 'Answer',
			'country' => 'Country',
			'is_finished' => 'Is Finished',
			'role' => 'Role',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('activation_code',$this->activation_code,true);
		$criteria->compare('forgotten_password_code',$this->forgotten_password_code,true);
		$criteria->compare('forgotten_password_time',$this->forgotten_password_time,true);
		$criteria->compare('remember_code',$this->remember_code,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('is_finished',$this->is_finished,true);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * check the password
	 * @param password
	 * @return true or false
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password, $this->salt)==$this->password;
	}

	/**
     * password hash
     * @param password
     * @param salt for password
     * @return hashed password
     */
	private function hashPassword($password, $salt)
	{
		return md5($salt.$password);
	}
}