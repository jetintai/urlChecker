<?php
/*
 * Файл view-шаблона modules/admin/views/order/index.php
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Url rules list';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'url',
        'frequency',
        'repeat_count',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}'
        ],

    ],
]);
?>
<a href="<?= \yii\helpers\Url::to(['/']) ?>">Add new url-rule</a>