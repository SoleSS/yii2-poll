<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model soless\poll\models\PsPoll */

$this->title = 'Результаты';
$this->params['breadcrumbs'][] = ['label' => 'Голосования', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ps-poll-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="results-wrap">
        <h2><?= $model->results['caption'] ?></h2>
        <div class="msg-wrap"><?= $model->results['msg'] ?></div>
        <?php if (!empty($model->results['data'])) : ?>
            <div class="results-wrap">
                <?php foreach ($model->results['data'] as $result) : ?>
                    <div class="result-wrap">
                        <div class="title-wrap"><?= $result['title'] ?></div>
                        <div class="description-wrap"><?= $result['description'] ?></div>
                        <div class="count-wrap"><?= $result['count'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
