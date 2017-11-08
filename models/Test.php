<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/9
 * Time: 20:39
 */

namespace app\models;

use yii\db\ActiveRecord;
class Test extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%test}}";
    }
}