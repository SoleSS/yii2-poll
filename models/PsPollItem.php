<?php

namespace soless\poll\models;

/**
 * This is the model class for table "ps_poll_item".
 *
 * @property int $id
 * @property int $ps_poll_id id Голосования
 * @property string $title Наименование
 * @property string|null $description Описание
 * @property string|null $amp_description Описание [AMP]
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property PsPoll $psPoll
 * @property PsPollItemHit[] $psPollItemHits
 */
class PsPollItem extends base\PsPollItem
{
    /**
     * Gets query for [[PsPoll]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPoll()
    {
        return $this->hasOne(PsPoll::className(), ['id' => 'ps_poll_id']);
    }

    /**
     * Gets query for [[PsPollItemHits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItemHits()
    {
        return $this->hasMany(PsPollItemHit::className(), ['ps_poll_item_id' => 'id']);
    }
}
