<?php
use yii\db\Migration;

/**
 * Handles the creation of table `cms_category`.
 */
class m200209_094500_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $options = null;
        if ($this->db->driverName === 'mysql') {
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ps_poll', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Наименование'),
            'description' => $this->text()->comment('Описание'),
            'amp_description' => $this->text()->comment('Описание [AMP]'),
            'params' => $this->json()->comment('Доп. параметры'),
            'created_at' => $this->datetime()->notNull()->comment('Дата создания'),
            'updated_at' => $this->datetime()->notNull()->comment('Дата обновления'),
        ], $options);

        $this->createIndex('idx-ps_poll-created_at', 'ps_poll', 'created_at');
        $this->createIndex('idx-ps_poll-updated_at', 'ps_poll', 'updated_at');

        $this->createTable('ps_poll_item', [
            'id' => $this->primaryKey(),
            'ps_poll_id' => $this->integer()->notNull()->comment('id Голосования'),
            'title' => $this->string(255)->notNull()->comment('Наименование'),
            'description' => $this->text()->comment('Описание'),
            'amp_description' => $this->text()->comment('Описание [AMP]'),
            'created_at' => $this->datetime()->notNull()->comment('Дата создания'),
            'updated_at' => $this->datetime()->notNull()->comment('Дата обновления'),
        ], $options);

        $this->createIndex('idx-ps_poll_item-created_at', 'ps_poll_item', 'created_at');
        $this->createIndex('idx-ps_poll_item-updated_at', 'ps_poll_item', 'updated_at');
        $this->createIndex('idx-ps_poll_item-ps_poll_id', 'ps_poll_item', 'ps_poll_id');
        $this->addForeignKey('fk-ps_poll_item-ps_poll_id', 'ps_poll_item', 'ps_poll_id', 'ps_poll', 'id', 'CASCADE');

        $this->createTable('ps_poll_item_hit', [
            'id' => $this->primaryKey(),
            'ps_poll_item_id' => $this->integer()->notNull()->comment('id Варианта голосования'),
            'user_id' => $this->integer()->comment('id Пользователя'),
            'ip' => $this->binary(16)->comment('IP пользователя'),
            'x_forwarded_ip' => $this->binary(16)->comment('IP пользователя'),
            'created_at' => $this->datetime()->notNull()->comment('Дата создания'),
        ], $options);

        $this->createIndex('idx-ps_poll_item_hit-created_at', 'ps_poll_item_hit', 'created_at');
        $this->createIndex('idx-ps_poll_item_hit-ps_poll_item_id', 'ps_poll_item_hit', 'ps_poll_item_id');
        $this->createIndex('idx-ps_poll_item_hit-ip', 'ps_poll_item_hit', 'ip');
        $this->createIndex('idx-ps_poll_item_hit-x_forwarded_ip', 'ps_poll_item_hit', 'x_forwarded_ip');
        $this->addForeignKey('fk-ps_poll_item_hit-ps_poll_item_id', 'ps_poll_item_hit', 'ps_poll_item_id', 'ps_poll_item', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ps_poll_item_hit-ps_poll_item_id', 'ps_poll_item_hit');
        $this->dropForeignKey('fk-ps_poll_item-ps_poll_id', 'ps_poll_item');

        $this->dropTable('ps_poll_item_hit');
        $this->dropTable('ps_poll_item');
        $this->dropTable('ps_poll');
    }
}
