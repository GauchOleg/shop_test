<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 25.01.2018
 * Time: 20:54
 */

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Product;
use yii\web\HttpException;

class ProductController extends AppController {

    public function actionView($id) {
//        $id = Yii::$app->request->get('id');
        $product = Product::findOne($id);

        if (empty($product)) {
            throw new HttpException(404,'Такой товар не существует');
        }

        $recommends = Product::find()->where(['hit' => 1])->limit(6)->all();
        $this->setMeta('Shop | ' . mb_strimwidth($product->name,0,70), $product->keywords, $product->description);
//        $product = Product::find()->with('category')->where(['id' => $id])->limit(1)->one();
        return $this->render('view',compact('product','recommends'));
    }

}