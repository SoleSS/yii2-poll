<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \mihaildev\elfinder\ElFinder;
use \mihaildev\ckeditor\CKEditor;
use \soless\poll\models\PsPoll;

/* @var $this yii\web\View */
/* @var $model soless\poll\models\PsPoll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ps-poll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'standard',
            'inline' => false,
            'height' => '300px',
            'allowedContent' => true,
        ]),
    ]); ?>

    <?= $form->field($model, 'type')->dropDownList(PsPoll::TYPE_TXT) ?>

    <?= $form->field($model, 'status')->dropDownList(PsPoll::STATUS_TXT) ?>

    <?= $form->field($model, 'poll_up')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата начала опроса'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>

    <?= $form->field($model, 'poll_down')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата окончания опроса'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
