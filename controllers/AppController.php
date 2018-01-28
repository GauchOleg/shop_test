<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 24.01.2018
 * Time: 20:59
 */

namespace app\controllers;


use yii\web\Controller;

class AppController extends Controller
{
    protected function setMeta($title = null,$keyword = null, $description = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => "$keyword"]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => "$description"]);
    }

}