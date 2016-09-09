<?php

use yii\db\Migration;

class m000000_000001_addI18nTables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%source_message}}', [
            'id'                    => $this->primaryKey()->comment('ID основного сообщения'),
            'hash'                  => $this->string(32)->notNull()->defaultValue('')->comment('Хеш сообщения'),
            'category'              => $this->string()->comment('Категория сообщения'),
            'message'               => $this->text()->comment('Сообщение'),
            'location'              => $this->text()->comment('Путь'),
        ], $tableOptions);

        $this->createTable('{{%message}}', [
            'id'                    => $this->integer()->comment('ID перевода'),
            'language'              => $this->string(16)->notNull()->defaultValue('')->comment('Язык перевода'),
            'hash'                  => $this->string()->comment('Хеш перевода'),
            'translation'           => $this->text()->comment('Перевод'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%message}}');
        $this->dropTable('{{%source_message}}');
    }
}
