<?php

namespace soless\poll\models;

use \soless\poll\helpers\AMP;


/**
 * This is the model class for table "ps_poll".
 *
 * @property int $id
 * @property string $title Наименование
 * @property string|null $description Описание
 * @property string|null $amp_description Описание [AMP]
 * @property string|null $params Доп. параметры
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 * @property int|null $type Тип голосования
 * @property int $status Состояние опроса
 * @property string|null $poll_up Дата начала опроса
 * @property string|null $poll_down Дата окончания опроса
 *
 * @property PsPollItem[] $psPollItems
 */
class PsPoll extends base\PsPoll
{
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');

        $ampized = AMP::encode($this->description, (\Yii::$app->params['frontendFilesRoot'] ?? null));
        $this->amp_description = $ampized['content'];

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->setPsPollItems();
    }

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const STATUS_TXT = [
        self::STATUS_UNPUBLISHED => 'Не доступен',
        self::STATUS_PUBLISHED => 'Доступен',
    ];

    const TYPE_RESULTS_AVAILABLE_AFTER_POLL = 0;
    const TYPE_RESULTS_AVAILABLE_AFTER_PUBLISH_DOWN = 1;

    const TYPE_TXT = [
        self::TYPE_RESULTS_AVAILABLE_AFTER_POLL => 'Результаты доступны сразу после голосования',
        self::TYPE_RESULTS_AVAILABLE_AFTER_PUBLISH_DOWN => 'Результаты доступны только после окончания периода голосования',
    ];

    public function isPollAvailable () {
        return strtotime($this->poll_up) <= time() && strtotime($this->poll_down) >= time();
    }
    public function isResultAvailable () {
        switch ($this->type) {
            case self::TYPE_RESULTS_AVAILABLE_AFTER_POLL:
                return true;
                break;
            case self::TYPE_RESULTS_AVAILABLE_AFTER_PUBLISH_DOWN:
                return strtotime($this->poll_down) < time();
                break;
        }
    }

    public function setPsPollItems() {

        return true;
    }

    /**
     * Gets query for [[PsPollItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItems()
    {
        return $this->hasMany(PsPollItem::className(), ['ps_poll_id' => 'id']);
    }
}
