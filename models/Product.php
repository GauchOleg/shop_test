<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 23.01.2018
 * Time: 21:45
 */

namespace app\models;


use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'product';
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

}