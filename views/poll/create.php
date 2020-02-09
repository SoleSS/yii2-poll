<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model soless\poll\models\PsPoll */

$this->title = 'Create Ps Poll';
$this->params['breadcrumbs'][] = ['label' => 'Ps Polls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ps-poll-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
