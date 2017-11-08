<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/18
 * Time: 11:14
 */

namespace app\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%cart}}";
    }

    public function rules()
    {
        return [
            [['productid', 'productnum', 'userid', 'price'], 'required'],
            ['createtime', 'safe'],
        ];
    }
}