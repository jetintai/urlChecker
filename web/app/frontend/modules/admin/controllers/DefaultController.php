<?php

namespace frontend\modules\admin\controllers;

use common\components\jobs\CheckUrlDirector;
use common\components\jobs\CheckUrlJob;
use common\models\Check;
use common\models\Url;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends AdminController
{

    /**
     * queue process name for supervisor
     */
    const YII_CHECK_PROCESS = 'yii-check-process';
    /**
     * queue process name for supervisor
     */
    const YII_DIRECTOR_PROCESS = 'yii-director-process';

    /**
     *
     * @return string
     */
    public function actionIndex()
    {
        $service['STATUS'] = array();
        $service['PROCESS'][] = shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf status " . self::YII_CHECK_PROCESS);
        $service['PROCESS'][] = shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf status " . self::YII_DIRECTOR_PROCESS);
        $service['STATUS'] = 'RUNNING';
        foreach ($service['PROCESS'] as $process)
            if (strpos($process, 'RUNNING') == 0) {
                $service['STATUS'] = 'STOPED';
                break;
        }
        return $this->render('index', ['service' => $service]);
    }

    /**
     * run supervisors process
     */
    public function actionRunService() {
        shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf start " . self::YII_CHECK_PROCESS);
        shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf start " . self::YII_DIRECTOR_PROCESS);
        $this->redirect( \yii\helpers\Url::to( ['/admin'] ) );
    }

    /**
     * stop supervisor process
     */
    public function actionStopService() {
        shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf stop " . self::YII_CHECK_PROCESS);
        shell_exec("supervisorctl -c /etc/supervisor/conf.d/supervisord.conf stop " . self::YII_DIRECTOR_PROCESS);
        shell_exec("rm -rf /code/app/console/log/*");
        Yii::$app->directorQueue->clear();
        Yii::$app->checkQueue->clear();
        $this->redirect( \yii\helpers\Url::to( ['/admin'] ) );
    }

    /**
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete() {
        $model = Url::findOne( Yii::$app->request->get('id') );
        $model->delete();
        $this->redirect( \yii\helpers\Url::to( ['default/url-list'] ) );
    }

    /**
     * remove all checks attempts rows
     */
    public function actionClearChecks() {
        Check::deleteAll();
        $this->redirect( \yii\helpers\Url::to( ['default/check-list'] ) );
    }

    /**
     * @return string
     */
    public function actionUrlList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Url::find(),
            'pagination' => [
                'pageSize' => 10
            ],
        ]);
        return $this->render('url_list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionCheckList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Check::find(),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_request' => SORT_ASC
                ]
            ]
        ]);
        return $this->render('check_list', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
