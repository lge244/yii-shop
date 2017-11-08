<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/13
 * Time: 14:26
 */

namespace app\models;

use yii\db\ActiveRecord;

class Profile extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%profile}}";
    }
}