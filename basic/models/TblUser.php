<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\rbac\PhpManager;
use app\rbac\rules\GroupRule;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $role_id
 * @property string $authKey
 * @property boolean $activation_status
 * @property string $activation_key
 *
 * @property TblRoles $role
 */
class TblUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_LOGIN = 'login';
    const NOT_ENOUGH_RIGHTS_MESSAGE = 'У Вас не достаточно прав для выполнения этого действия';
    const ACTIVATION_FAILED = 'При активации возникла ошибка. Попробуйте зарегистрироваться ещё раз.';
    const ACTIVATION_REQUIRED = 'Для входа необходимо активировать свою учётную запись, пройдя по ссылке, указанной в письме, отправленном на Ваш электронный адрес, указанный при регистрации.';

    public $hashPassword = false;
    public $verifyCode;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['username', 'password', 'name', 'phone', 'email', 'role_id', 'verifyCode'];
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'name', 'phone', 'email', 'role_id'], 'required'],
            [['role_id'], 'in', 'range' => [1, 2]],
            [['username', 'name', 'email', 'authKey'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255, 'min' => 3],
            [['phone'], 'string', 'max' => 20],
            [['username'], 'unique', 'on' => self::SCENARIO_CREATE],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'on' => self::SCENARIO_CREATE],
            // email has to be a valid email address
            ['email', 'email'],
            [['activation_key'], 'string', 'max' => 255],
            [['activation_status'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'username' => 'Логин',
            'password' => 'Пароль',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'role_id' => 'Роль',
            'authKey' => 'Auth Key',
            'verifyCode' => 'Код подтверждения ("капча")',
            'activation_status' => 'Статус активации',
            'activation_key' => 'Ключ активации'
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken is not implemented."');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey();
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUserName($username)
    {
        // var_dump(static::findOne(['username' => $username]));
        // die;
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        // var_dump($this->password);
        // die;
        return \Yii::$app->security->validatePassword($password, $this->password);
    }



    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            if(!$this->getUser()->activation_status){
                return false;
            }

            $user = Yii::$app->user->login($this->getUser(), /*$this->rememberMe ? 3600*24*30 :*/ 0);


            return $user;
        }
        return false;
    }




    public function beforeSave($insert)
    {

       // $auth = Yii::$app->authManager;
       //  var_dump($auth->checkAccess($this->id, 'updateUser'));
        // die;

        if(parent::beforeSave($insert)){
            if($this->hashPassword){
                $this->password = \Yii::$app->security->generatePasswordHash($this->password, 10);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
         parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(TblRoles::className(), ['id' => 'role_id']);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = TblUser::findByUsername($this->username);
        }

        return $this->_user;
    }

}
