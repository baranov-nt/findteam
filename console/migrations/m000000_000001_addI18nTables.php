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

        $this->createTable('{{%content}}', [
            'id'                    => $this->primaryKey()->comment('ID Контента'),
            'category'              => $this->string(16)->notNull()->defaultValue('content')->comment('Категория'),
            'description'           => $this->string()->notNull()->comment('Описание контента'),
            'location'              => $this->string()->notNull()->comment('Путь'),
            'message'               => $this->text()->notNull()->comment('Сообщение'),
            'user_id'               => $this->integer()->comment('ID Пользователя'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        $this->addForeignKey('content_user_fk', '{{%content}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%source_message}}', [
            'id'                    => $this->primaryKey()->comment('ID основного сообщения'),
            'hash'                  => $this->string(32)->notNull()->defaultValue('')->comment('Хеш сообщения'),
            'category'              => $this->string()->comment('Категория сообщения'),
            'message'               => $this->text()->comment('Сообщение'),
            'location'              => $this->text()->comment('Путь'),
            'content_id'            => $this->integer()->comment('ID Контента'),
        ], $tableOptions);

        $this->addForeignKey('source_message_content_fk', '{{%source_message}}', 'content_id', '{{%content}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%message}}', [
            'id'                    => $this->integer()->comment('ID перевода'),
            'language'              => $this->string(16)->notNull()->defaultValue('')->comment('Язык перевода'),
            'hash'                  => $this->string()->comment('Хеш перевода'),
            'translation'           => $this->text()->comment('Перевод'),
        ], $tableOptions);

        $this->addForeignKey('message_source_message_fk', '{{%message}}', 'id', '{{%source_message}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%message}}');
        $this->dropTable('{{%source_message}}');
        $this->dropTable('{{%content}}');
    }
}
