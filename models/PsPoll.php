<?php

namespace soless\poll\models;


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
 *
 * @property PsPollItem[] $psPollItems
 */
class PsPoll extends base\PsPoll
{
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
