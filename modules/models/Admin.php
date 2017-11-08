<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/10
 * Time: 19:58
 */

namespace app\modules\models;

use yii\db\ActiveRecord;

use Yii;

class Admin extends ActiveRecord
{
    public $rememberMe = false;
    public $repass = '';

    //连接的数据表
    public static function tableName()
    {
        return "{{%admin}}";
    }

    public function attributeLabels()
    {
        return [
            'adminuser' => '管理员账号',
            'adminemail' => '管理员邮箱',
            'adminpass' => '管理员密码',
            'repass' => '确认密码',
            'createtime' => '',
        ];
    }

    /*
     * @rules：验证规则
     *
     * adminuser：管理员账号
     *
     * adminpass：管理员密码
     *
     * required：必须要有
     *
     * validatePass：回调验证密码结果
     *
     * email：邮箱格式
     *
     * adminemail：管理员邮箱
     *
     * validateEmail：回调验证邮箱结果
     */
    public function rules()
    {
        return [
            ['adminuser', 'required', 'message' => '管理员账号不能为空！', 'on' => ['login', 'seekPass', 'adminAdd', 'changeemail','changepass']],
            ['adminpass', 'required', 'message' => '管理员密码不能为空！', 'on' => ['login', 'adminAdd', 'changeemail','changepass']],
            ['rememberMe', 'boolean', 'on' => ['login']],
            ['adminpass', 'validatePass', 'on' => ['login', 'changeemail']],
            ['adminemail', 'required', 'message' => '邮箱不能为空啊！', 'on' => ['seekPass', 'adminAdd', 'changeemail']],
            ['adminemail', 'email', 'message' => '邮箱格式不正确！', 'on' => ['seekPass', 'adminAdd', 'changeemail']],
            ['adminemail', 'unique', 'message' => '邮箱已经被使用了，换一个吧！', 'on' => ['adminAdd', 'changeemail']],
            ['adminuser', 'unique', 'message' => '账号已经被使用了，换一个吧！', 'on' => ['adminAdd']],
            ['adminemail', 'validateEmail', 'on' => ['seekPass']],
            ['repass', 'required', 'message' => '确认密码不能为空！', 'on' => ['adminAdd','changepass']],
            ['repass', 'compare', 'compareAttribute' => 'adminpass', 'message' => '两次密码输入不一致！', 'on' => ['adminAdd','changepass']],
            ['createtime', 'required', 'message' => '创建时间为空', 'on' => 'adminAdd'],

        ];
    }

    //登陆的数据验证
    public function validatePass()
    {
        if (!$this->hasErrors()) {
            $data = self::find()->where('adminuser = :user and adminpass = :pass', [":user" => $this->adminuser, ":pass" => md5($this->adminpass)])->one();
            if (is_null($data)) {
                $this->addError("adminpass", "用户名或密码错误！");
            }
        }
    }

    //找回密码的数据验证
    public function validateEmail()
    {
        if (!$this->hasErrors()) {
            $data = self::find()->where('adminuser=:user and adminemail = :email', [":user" => $this->adminuser, ":email" => $this->adminemail])->one();
            if (is_null($data)) {
                $this->addError('adminemail', '邮箱和账号不匹配哦！');
            }
        }
    }

    //验证登陆，写入session 显示错误信息
    public function login($data)
    {
        //设置登陆的场景
        $this->scenario = "login";
        if ($this->load($data) && $this->validate()) {
            //做点有意义的事
            //判断rememberMe是否被选中，选中过期时间给一天的时间，否则就给0
            $lifetime = $this->rememberMe ? 24 * 3600 : 0;

            //设置session
            $session = Yii::$app->session;

            //设置保存session id的cookie有效期
            session_set_cookie_params($lifetime);

            //给session存值
            $session['admin'] = [
                'adminuser' => $this->adminuser,
                'isLogin' => 1,
            ];
            //更新登陆时间和登陆ip
            $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)], 'adminuser=:user', [':user' => $this->adminuser]);
            return (bool)$session['admin']['isLogin'];
        }
        return false;
    }

    //验证找回密码，成功时发送邮件
    public function seekpass($data)
    {
        //设置找回密码场景
        $this->scenario = "seekPass";
        if ($this->load($data) && $this->validate()) {
            //做点有意义的事

        }
        return false;
    }

    //添加管理员
    public function reg($data)
    {
        //设置添加管理员场景
        $this->scenario = 'adminAdd';
        //验证前面的 rules 规则有没有出错
        if ($this->load($data) && $this->validate()) {
            //把密码MD5加密一下
            $this->adminpass = md5($this->adminpass);
            // 存入数据库
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    //修改邮箱
    public function changeEmail($data)
    {
        //设置修改管理员邮箱场景
        $this->scenario = 'changeemail';
        // load 接收数据，validate 验证 rule 规则 if判断结果
        if ($this->load($data) && $this->validate()) {
            //还回布尔，更新数据
            return (bool)$this->updateAll(['adminemail' => $this->adminemail], 'adminuser=:user', [':user' => $this->adminuser]);
        }

        return false;
    }

    //修改密码
    public function changePass($data)
    {
        //设置修改管理员密码场景
        $this->scenario = 'changepass';
        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminpass' => md5($this->adminpass)], 'adminuser = :user', [':user' => $this->adminuser]);
        }
        return false;
    }

}