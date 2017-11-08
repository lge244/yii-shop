<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/14
 * Time: 21:22
 */

namespace app\models;

use yii\db\ActiveRecord;

use yii\helpers\ArrayHelper;

class Category extends ActiveRecord
{


    public static function tableName()
    {
        return "{{%category}}";
    }

    public function attributeLabels()
    {
        return [
            'pid' => '上级分类',
            'title' => '分类名称',
        ];
    }

    public function rules()
    {
        return [
            ['pid', 'required', 'message' => '上级分类不能为空！', 'on' => ['addcate']],
            ['title', 'required', 'message' => '类名不能为空！', 'on' => ['addcate']],
            ['createtime', 'safe', 'on' => ['addcate']],
            ['title', 'unique', 'message' => '类名不能重复！', 'on' => ['addcate']],
        ];
    }

    public function add($data, $scenario = 'addcate')
    {
        $this->scenario = $scenario;
        $data['Category']['createtime'] = time();
        if ($this->load($data) && $this->validate()) {
            if ($this->save(false))
                return true;
        }
        return false;
    }

    //查询到数据
    public function getDate()
    {
        $cates = self::find()->all();
        //把上面从数据库里查询到的对象转成数组
        $cates = ArrayHelper::toArray($cates);
        return $cates;
    }

    //给数据分开排列
    public function getTree($cates, $pid = 0)
    {
        $tree = [];
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $tree[] = $cate;
                $tree = array_merge($tree, $this->getTree($cates, $cate['cateid']));
            }
        }
        return $tree;
    }

    //给排列好的设置对应的前缀
    public function setPrefix($data, $p = '|--')
    {
        $tree = [];
        $num = 1;
        //0是pid，1是前缀的个数
        $prefix = [0 => 1];
        while ($val = current($data)) {
            $key = key($data);
            if ($key > 0) {
                if ($data[$key - 1]['pid'] != $val['pid']) {
                    $num++;
                }
            }
            //判断当前数组父类级别，是否存在$prefix数组中，
            if (array_key_exists($val['pid'], $prefix)) {
                //这个级别所拥有的前缀个数
                $num = $prefix[$val['pid']];
            }
            $val['title'] = str_repeat($p, $num) . $val['title'];
            $prefix[$val['pid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }

    public function setOptions()
    {
        $data = $this->getDate();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        $options = ['添加顶级分类'];
        foreach ($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }

    public function setList()
    {
        $data = $this->getDate();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        return $tree;
    }

    public static function getMenu()
    {
        $top = self::find()->where('pid=:id', [':id' => 0])->limit(10)->asArray()->all();
        $data = [];
        foreach ($top as $k => $cate) {
            $cate['children'] = self::find()->where('pid = :id', [':id' => $cate['cateid']])->limit(10)->asArray()->all();
            $data[$k] = $cate;
        }
        return $data;
    }
}