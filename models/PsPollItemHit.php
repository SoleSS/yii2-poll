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
 * @property-read PsPoll $psPoll
 */
class PsPollItemHit extends base\PsPollItemHit
{
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        return parent::beforeValidate();
    }

    /**
     * Gets query for [[PsPollItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPsPollItem()
    {
        return $this->hasOne(PsPollItem::class, ['id' => 'ps_poll_item_id']);
    }

    public function getPsPoll() {
        return $this->hasOne(PsPoll::class, ['id' => 'ps_poll_id'])
            ->via('psPollItem');
    }
}
