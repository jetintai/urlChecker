<?php

namespace backend\controllers;

use common\components\jobs\CheckUrlDirector;
use common\models\LoginForm;
use Yii;
use yii\base\BaseObject;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\queue\Queue;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->checkQueue->on(Queue::EVENT_AFTER_ERROR, function ($event) {
            $queue = $event->sender;
            $queue->delay(CheckUrlDirector::REDIS_MESSAGE_DELAY)->push($event->job);
        });

        $directorQueue = Yii::$app->directorQueue->redis;
        //if ($checkQueue->redis && ( $checkQueue->redis->llen( "$checkQueue->channel.messages" ) ))
        if ($directorQueue->redis) {
            echo "connection count " . $directorQueue->redis->llen( "$directorQueue->channel.messages" );
        }

        Yii::$app->directorQueue->push(new CheckUrlDirector([
            'runCounter' => 0,
        ]));

        return 'OK';
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
