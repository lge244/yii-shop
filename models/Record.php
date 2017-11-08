<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/20
 * Time: 20:59
 */

namespace app\models;

use yii\db\ActiveRecord;

class Record extends ActiveRecord
{

    public function rules()
    {
        return [
            [['orderid', 'paymethod', 'express', 'status', 'createtime', 'address'], 'required', 'on' => ['add']],
            ['createtime', 'safe', 'on' => ['add']],
            ['expressno', 'required', 'message' => '快递单号不能为空', 'on' => ['send']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'expressno' => '快递单号',
        ];
    }

    public static function tableName()
    {
        return "{{%record}}";
    }

}