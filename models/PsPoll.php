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
 * @property-read array $results Результаты опроса
 * @property-read PsPollItemHit[] $psPollItemHits Данные о голосах
 * @property-read array $optionsArray Массив вариантов голосования
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

    public function getResults() {
        if (!$this->isResultAvailable()) return [
            'caption' => 'Спасибо за ваш голос',
            'msg' => 'Результаты станут доступны: '. date('d\.m\.Y H:i:s', strtotime($this->poll_down)),
            'data' => [],
        ];

        $totalVotes = PsPollItemHit::find()
            ->joinWith(['psPollItem', ])
            ->where(['ps_poll_item.ps_poll_id' => $this->id])
            ->count();

        return [
            'caption' => 'Спасибо за ваш голос',
            'msg' => null,
            'totalVotes' => $totalVotes,
            'data' => PsPollItemHit::find()
                ->select([
                    'ps_poll_item_hit.ps_poll_item_id',
                    'title' => 'ps_poll_item.title',
                    'description' => 'ps_poll_item.description',
                    'count' => 'COUNT(*)',
                    'proc' => 'ROUND(COUNT(*)/'. ($totalVotes == 0 ? 1 : $totalVotes) .' * 100, 0)',
                ])
                ->joinWith(['psPollItem', ])
                ->where(['ps_poll_item.ps_poll_id' => $this->id])
                ->groupBy([
                    'ps_poll_item_hit.ps_poll_item_id',
                    'ps_poll_item.title',
                    'ps_poll_item.description'
                ])
                ->asArray()
                ->all()
        ];
    }

    public function getOptionsArray() {
        return \yii\helpers\ArrayHelper::map($this->psPollItems, 'id', 'title');
    }

    /**
     * Gets query for [[PsPollItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItems()
    {
        return $this->hasMany(PsPollItem::class, ['ps_poll_id' => 'id']);
    }

    /**
     * Gets query for [[PsPollItemHits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItemHits() {
        return $this->hasMany(PsPollItemHit::class, ['ps_poll_item_id' => 'ps_poll_id'])
            ->via('psPollItems');
    }

    public static function asArray() {
        return \yii\helpers\ArrayHelper::map(static::find()->select(['id', 'title'])->asArray()->all(), 'id', 'title');
    }
}
