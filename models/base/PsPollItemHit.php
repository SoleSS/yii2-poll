<?php

namespace soless\poll\models\base;

use Yii;

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
class PsPollItemHit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ps_poll_item_hit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ps_poll_item_id', 'created_at'], 'required'],
            [['ps_poll_item_id', 'user_id'], 'integer'],
            [['ip', 'x_forwarded_ip'], 'string'],
            [['created_at'], 'safe'],
            [['ps_poll_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PsPollItem::className(), 'targetAttribute' => ['ps_poll_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ps_poll_item_id' => 'id Варианта голосования',
            'user_id' => 'id Пользователя',
            'ip' => 'IP пользователя',
            'x_forwarded_ip' => 'IP пользователя',
            'created_at' => 'Дата создания',
        ];
    }

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
