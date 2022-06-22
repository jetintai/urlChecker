<?php

namespace frontend\modules\admin\controllers;

use Yii;
use yii\web\Controller;

/**
 * Authorise class controller for the `admin` module
 */
class AdminController extends Controller
{
    public function beforeAction($action) {
        if (Yii::$app->user->isGuest) {
            $this->redirect('/site/login');
            return false;
        }
        return parent::beforeAction($action);
    }
}
