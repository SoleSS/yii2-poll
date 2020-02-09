<?php

namespace soless\poll\models\base;

use Yii;

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
class PsPoll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ps_poll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'updated_at'], 'required'],
            [['description', 'amp_description'], 'string'],
            [['params', 'created_at', 'updated_at', 'poll_up', 'poll_down'], 'safe'],
            [['type', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'description' => 'Описание',
            'amp_description' => 'Описание [AMP]',
            'params' => 'Доп. параметры',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'type' => 'Тип голосования',
            'status' => 'Состояние опроса',
            'poll_up' => 'Дата начала опроса',
            'poll_down' => 'Дата окончания опроса',
        ];
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
