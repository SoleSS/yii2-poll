<?php

namespace soless\poll\models\base;

use Yii;

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
class PsPollItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ps_poll_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ps_poll_id', 'title', 'created_at', 'updated_at'], 'required'],
            [['ps_poll_id'], 'integer'],
            [['description', 'amp_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['ps_poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => PsPoll::className(), 'targetAttribute' => ['ps_poll_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ps_poll_id' => 'id Голосования',
            'title' => 'Наименование',
            'description' => 'Описание',
            'amp_description' => 'Описание [AMP]',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

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
