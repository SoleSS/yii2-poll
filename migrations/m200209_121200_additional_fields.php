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

        $this->addColumn('ps_poll', 'type', $this->integer()->comment('Тип голосования')->defaultValue(0));
        $this->addColumn('ps_poll', 'status', $this->integer()->notNull()->defaultValue(0)->comment('Состояние опроса'));
        $this->addColumn('ps_poll', 'poll_up', $this->datetime()->comment('Дата начала опроса'));
        $this->addColumn('ps_poll', 'poll_down', $this->datetime()->comment('Дата окончания опроса'));

        $this->createIndex('idx-ps_poll-status', 'ps_poll', 'type');
        $this->createIndex('idx-ps_poll-status', 'ps_poll', 'status');
        $this->createIndex('idx-ps_poll-poll_up', 'ps_poll', 'poll_up');
        $this->createIndex('idx-ps_poll-poll_down', 'ps_poll', 'poll_down');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ps_poll', 'type');
        $this->dropColumn('ps_poll', 'status');
        $this->dropColumn('ps_poll', 'poll_up');
        $this->dropColumn('ps_poll', 'poll_down');
    }
}
