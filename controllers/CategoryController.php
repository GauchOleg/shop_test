<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 24.01.2018
 * Time: 20:59
 */

namespace app\controllers;
use app\models\Category;
use app\models\Product;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class CategoryController extends AppController
{

    public function actionIndex()
    {
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();
        $this->setMeta('Shop');
//        debug($hits);
        return $this->render('index',compact('hits'));
    }

    public function actionView($id)
    {
//        $id = Yii::$app->request->get('id');

        $category = Category::findOne($id);

        if (empty($category)) {
            throw new HttpException(404, 'Такой категории не существует');
        }

//        $products = Product::find()->where(['category_id' => $id])->all();
        $query = Product::find()->where(['category_id' => $id]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 10, 'forcePageParam' => false, 'pageSizeParam' => false]);

        $products = $query->offset($pages->offset)->limit($pages->limit)->all();

        $this->setMeta('Shop | ' . $category->name, $category->keywords, $category->description);
//        debug($products);
        return $this->render('view',compact('products','category','pages'));
    }

    public function actionSearch() {
        $query = trim(Yii::$app->request->get('query'));

        if (!$query)
            return $this->render('search');

        $this->setMeta('Shop | Search - ' . $query);

        $result = Product::find()->where(['like', 'name', $query]);
        $pages = new Pagination([
            'totalCount' => $result->count(),
            'pageSize' => 6,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
        $products = $result->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('search',compact('products','pages','query'));
    }
}