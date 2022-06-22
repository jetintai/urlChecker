<?php
/*
 * Файл view-шаблона modules/admin/views/order/index.php
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Check list';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'url.url',
        'http_code',
        'date_request',
        'try_number',
    ],
]);
?>
<a href="<?= \yii\helpers\Url::to(['/admin/default/clear-checks']) ?>">Clear checks</a>
