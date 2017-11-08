<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;

class User extends ActiveRecord
{
    public $repass;
    public $loginName;
    public $rememberMe = true;

    public static function tableName()
    {
        return "{{%user}}";
    }

    public function rules()
    {
        return [
            ['username', 'required', 'message' => '用户名不能为空！', 'on' => ['userAdd', 'qqreg']],
            ['loginName', 'required', 'message' => '账号不能为空！', 'on' => ['login']],
            ['openid', 'required', 'message' => 'openid不能为空！', 'on' => ['qqreg']],
            ['openid', 'unique', 'message' => 'openid已经存在！', 'on' => ['qqreg']],
            ['userpass', 'required', 'message' => '用户密码不能为空！', 'on' => ['userAdd', 'login', 'qqreg']],
            ['userpass', 'validatePass', 'on' => 'login'],
            ['useremail', 'required', 'message' => '用户邮箱不能为空！', 'on' => ['userAdd', 'qqreg']],
            ['username', 'unique', 'message' => '用户名已被使用！', 'on' => ['userAdd', 'qqreg']],
            ['useremail', 'unique', 'message' => '用户邮箱已被绑定！', 'on' => ['userAdd', 'qqreg']],
            ['useremail', 'email', 'message' => '邮箱格式不正确！', 'on' => ['userAdd', 'qqreg']],
            ['repass', 'required', 'message' => '确认密码不能为空！', 'on' => ['userAdd', 'qqreg']],
            ['repass', 'compare', 'compareAttribute' => 'userpass', 'message' => '两次密码输入不一致！', 'on' => ['userAdd', 'qqreg']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'userpass' => '用户密码',
            'useremail' => '用户邮箱',
            'repass' => '确认密码',
            'loginName' => '用户名或邮箱',
        ];
    }

    public function validatePass()
    {
        if (!$this->hasErrors()) {
            $data = self::find()->where('username=:name and userpass=:pass', [':name' => $this->loginName, ':pass' => md5($this->userpass)])->one();
            $data1 = self::find()->where('useremail=:email and userpass=:pass', [':email' => $this->loginName, ':pass' => md5($this->userpass)])->one();
            if (is_null($data) && is_null($data1)) {
                $this->addError('userpass', '账号或密码错误！');
            }
        }
    }

    public function reg($data, $scenario = 'userAdd')
    {
        $this->scenario = $scenario;
        if ($this->load($data) && $this->validate()) {
            $this->createtime = time();
            $this->userpass = md5($this->userpass);
            if ($this->save(false)) {
                $session = Yii::$app->session;
                $session['user'] = [
                    'loginName' => $this->username,
                    'isLogin' => 1,
                ];
                return (bool)$session['user']['isLogin'];
            }
            return false;
        }
        return false;
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }

    public function login($data)
    {
        $this->scenario = 'login';
        if ($this->load($data) && $this->validate()) {
            $lifetime = $this->rememberMe ? 24 * 3600 : 0;
            $session = Yii::$app->session;
            $session['user'] = [
                'loginName' => $this->loginName,
                'isLogin' => 1,
            ];
            return (bool)$session['user']['isLogin'];
        }
        return false;
    }

}
