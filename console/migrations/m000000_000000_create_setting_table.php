<?php

use yii\db\Migration;

/**
 * Handles the creation for table `setting`.
 */
class m000000_000000_create_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id'                    => $this->primaryKey()->comment('ID'),
            'show_all_cities'       => $this->boolean()->defaultValue(true)->comment('Использовать все города'),
            'show_all_countries'    => $this->boolean()->defaultValue(true)->comment('Использовать все страны'),
        ], $tableOptions);

        $this->batchInsert('{{%setting}}', ['id'], [
            [1]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('setting');
    }
}
