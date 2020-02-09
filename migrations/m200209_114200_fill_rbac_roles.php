<?php

use yii\db\Migration;

/**
 * Class m190330_150757_fill_rbac_roles
 */
class m200209_114200_fill_rbac_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        try {
            $this->batchInsert('auth_item', ['name', 'type', 'created_at', 'updated_at',], [
                ['PsPollAdmin', 2, time(), time(),],
            ]);

            $this->batchInsert('auth_item_child', ['parent', 'child'], [
                ['Administrator', 'PsPollAdmin'],
            ]);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        try {
            $this->delete('auth_item', ['OR',
                ['name' => 'PsPollAdmin'],
            ]);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190330_150757_fill_rbac_roles cannot be reverted.\n";

        return false;
    }
    */
}
