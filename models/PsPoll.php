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
 * @property PsPollItem $items Варианты голосования
 *
 * @property PsPollItem[] $psPollItems
 */
class PsPoll extends base\PsPoll
{
    public $items;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['items', ], 'validateItems'];
        return $rules;
    }

    public function validateItems($attribute, $params)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'Варианты голосования должны быть массивом');
            return false;
        }

        if (!empty($this->$attribute)) foreach ($this->$attribute as $i => $item) {
            if (empty($item['title'])) $this->addError($attribute."[{$i}][title]", 'Заголовок обязателен для заполнения');
            return false;
        }
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['items'] = 'Варианты голосования';

        return $labels;
    }

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

    public function afterFind(){
        parent::afterFind();

        $this->setItems();
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
        if (empty($this->items)) return false;

        $oldItemsIds = PsPollItem::find()->select('id')->where(['ps_poll_id' => $this->id])->column();
        foreach ($this->items as $item) {
            if (empty($item['id'])) {
                $pollItem = new PsPollItem();
                $pollItem->ps_poll_id = $this->id;
            }
            else $pollItem = PsPollItem::findOne($item['id']);
            $pollItem->title = $item['title'];
            $pollItem->description = $item['description'];

            if (!empty($item['id']) && in_array($item['id'], $oldItemsIds)) {
                if (($key = array_search($item['id'], $oldItemsIds)) !== false) {
                    unset($oldItemsIds[$key]);
                }
            }
            if (!$pollItem->save()) \Yii::error($pollItem->errors);
        }

        if (!empty($oldItemsIds)) PsPollItem::deleteAll(['id' => $oldItemsIds]);
        return true;
    }

    public function setItems() {
        $result = [];
        foreach ($this->psPollItems as $item) {
            $result[] = [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
            ];
        }
        $this->items = $result;
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
