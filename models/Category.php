<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 23.01.2018
 * Time: 21:42
 */

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{

    public static function tableName()
    {
        return 'category';
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

}