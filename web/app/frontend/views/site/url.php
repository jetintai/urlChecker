<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \frontend\models\UrlForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Запись правила';
?><br>
<div class="row">
    <div class="col-md-12"><h4>Add url for check</h4></div>
</div><br>
<div class="row">
    <div class="col-md-5">
        <?php $form = ActiveForm::begin(['id' => 'url-form']); ?>

        <div class="form-group">
            <?= $form->field($model, 'url')->textInput(['autofocus' => true])->label('URL для проверки'); ?>
            <?= $form->field($model, 'frequency')->radioList([1 => '1 мин.', 5 => '5 мин.', 10 => '10 мин.'])->label('Частота проверки'); ?>
            <?= $form->field($model, 'repeat_count')->textInput()->label('Количество повторов в случае ошибки'); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Записать правило', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <br>

    <div class="col-md-12"><a href="<?= \yii\helpers\Url::to(['admin/default/url-list']) ?>">List of url-rules</a></div>
</div>
