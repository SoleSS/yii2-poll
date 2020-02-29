<?php
namespace soless\poll\widgets;

use soless\poll\models\PsPoll;

class AmpPollWidget extends \yii\base\Widget
{
    public $model;
    public $layout;

    public function init()
    {
        parent::init();
        if ($this->layout === null) {
            $this->layout = 'amp-poll';
        }
    }

    public function run()
    {
        return $this->render($this->layout, [
            'model' => $this->model,
        ]);
    }
}