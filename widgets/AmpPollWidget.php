<?php
namespace soless\poll\widgets;

use soless\poll\models\PsPoll;

class AmpPollWidget extends \yii\base\Widget
{
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('amp-poll', [
            'model' => $this->model,
        ]);
    }
}