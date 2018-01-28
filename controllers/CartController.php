<?php
/**
 * Created by PhpStorm.
 * User: developer-pc
 * Date: 26.01.2018
 * Time: 21:13
 */

namespace app\controllers;
use app\models\Product;
use app\models\Cart;
use app\models\OrderItems;
use app\models\Order;
use Yii;

class CartController extends AppController {

    public function actionAdd() {

        $id = Yii::$app->request->get('id');
        $product = Product::findOne($id);
        $qty = (int)Yii::$app->request->get('qty');
        $qty = !$qty ? 1 : $qty;
        if (empty($product))
            return false;

        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->addToCart($product, $qty);
//        debug($session['cart']);
//        debug($session['cart.qty']);
//        debug($session['cart.sum']);
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));

    }
    public function actionClear() {
        $session = Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }

    public function actionDelItem() {
        $this->layout = false;
        $id = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalc($id);
        return $this->render('cart-modal',compact('session'));
    }

    public function actionShow() {
        $session = Yii::$app->session;
        $session->open();
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }

    public function actionView() {
        $session = Yii::$app->session;
        $session->open();
        $this->setMeta('Корзина');
        $order = new Order();

        if ($order->load(Yii::$app->request->post())) {
            $order->qty = $session['cart.qty'];
            $order->sum = $session['cart.sum'];
//            debug(Yii::$app->request->post());
            if ($order->save()) {
                $order->saveOrderItems($session['cart'], $order->id);
                Yii::$app->session->setFlash('success', 'Ваш заказ принят');

//                Yii::$app->params['adminEmail'];

//                Yii::$app->mailer->compose('order',compact('session'))
//                    ->setFrom(['martletauto@gmail.com' => 'Магазин тестовый'])
//                    ->setTo($order->email)
//                    ->setSubject('Заказ')->send();

                $session->remove('cart');
                $session->remove('cart.qty');
                $session->remove('cart.sum');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error','Ошибка оформления заказа');
            }
        }

        return $this->render('view',compact('session','order'));
    }


}