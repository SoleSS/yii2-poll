<?php

namespace soless\poll\models;

use soless\poll\models\PsPollItemHit;
use Yii;
use yii\base\Model;

class VoteForm extends Model
{
    public $pollId;
    public $pollItemId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pollId', 'pollItemId', ], 'required'],
            [['pollId', 'pollItemId', ], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pollId' => 'id Опроса',
            'pollItemId' => 'Варианты',
        ];
    }

    public function prepareVote() {
        $vote = new PsPollItemHit([
            'ps_poll_item_id' => $this->pollItemId,
            'user_id' => Yii::$app->user->isGuest ? null : Yii::$app->user->id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'x_forwarded_ip' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        ]);

        return $vote;
    }
}
