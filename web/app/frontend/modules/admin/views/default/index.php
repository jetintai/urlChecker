<?php
/*
 * Файл view-шаблона modules/admin/views/order/index.php
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service dashboard';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-md-12">
        <br><br><h5>System services started at:</h5><br>
        <div>
            <? foreach ($service['PROCESS'] as $process): ?>
                <p><?= $process ?></p>
            <? endforeach; ?>
        </div>
        <br>
        <? if ($service['STATUS'] == 'RUNNING'): ?>
            <?= Html::a('Stop service', ['/admin/default/stop-service'], ['class' => 'btn btn-primary']) ?>
        <? else: ?>
            <?= Html::a('Run service', ['/admin/default/run-service'], ['class' => 'btn btn-primary']) ?>
        <? endif; ?>
    </div>
</div>