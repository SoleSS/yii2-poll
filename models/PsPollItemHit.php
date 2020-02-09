<?php

namespace soless\poll\models;


/**
 * This is the model class for table "ps_poll_item_hit".
 *
 * @property int $id
 * @property int $ps_poll_item_id id Варианта голосования
 * @property int|null $user_id id Пользователя
 * @property resource|null $ip IP пользователя
 * @property resource|null $x_forwarded_ip IP пользователя
 * @property string $created_at Дата создания
 *
 * @property PsPollItem $psPollItem
 */
class PsPollItemHit extends base\PsPollItemHit
{
    /**
     * Gets query for [[PsPollItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItem()
    {
        return $this->hasOne(PsPollItem::className(), ['id' => 'ps_poll_item_id']);
    }
}
