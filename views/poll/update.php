<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model soless\poll\models\PsPoll */

$this->title = 'Update Ps Poll: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ps Polls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ps-poll-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
