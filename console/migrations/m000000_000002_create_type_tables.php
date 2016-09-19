<?php

use yii\db\Migration;

class m000000_000002_create_type_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Категории специализаций пользователей и компаний */
        $this->createTable('{{%type_category}}', [
            'id'        => $this->primaryKey()->comment(Yii::t('app', 'ID категории')),
            'category'  => $this->string()->comment(Yii::t('app', 'Название категории'))
        ], $tableOptions);

        $this->batchInsert('{{%type_category}}', ['id', 'category'], [
            [1, Yii::t('app', 'Пользователь по умолчанию')],
            [2, Yii::t('app', 'Компания по умолчанию')],
        ]);

        /* Пользователи тип */
        $this->createTable('{{%type_user}}', [
            'id'            => $this->primaryKey()->comment(Yii::t('app', 'ID типа пользователя')),
            'type'          => $this->string()->comment(Yii::t('app', 'Тип пользователя')),
            'category_id'   => $this->integer()->comment(Yii::t('app', 'ID категории'))
        ], $tableOptions);

        $this->addForeignKey('type_user_category', '{{%type_user}}', 'category_id', '{{type_category}}', 'id',  'CASCADE', 'CASCADE');


        $this->batchInsert('{{%type_user}}', ['id', 'type', 'category_id'], [
            [1, Yii::t('app', 'Пользователь'), 1],
        ]);

        $this->createTable('{{%spec_user}}', [
            'id'        => $this->primaryKey()->comment(Yii::t('app', 'ID пользователя')),
            'type_id'   => $this->integer()->comment(Yii::t('app', 'Тип пользователя')),
            'user_id'   => $this->integer()->comment(Yii::t('app', 'Тип пользователя'))
        ], $tableOptions);

        $this->addForeignKey('spec_user_type', '{{%spec_user}}', 'type_id', '{{%type_user}}', 'id', 'CASCADE');
        $this->addForeignKey('spec_user', '{{%spec_user}}', 'user_id', '{{%profile_user}}', 'id', 'CASCADE');

        /* Компании тип */
        $this->createTable('{{%type_company}}', [
            'id'            => $this->primaryKey()->comment(Yii::t('app', 'ID типа компании')),
            'type'          => $this->string()->comment(Yii::t('app', 'Тип компании')),
            'category_id'   => $this->integer()->comment(Yii::t('app', 'ID категории'))
        ], $tableOptions);

        $this->addForeignKey('type_company_category', '{{%type_company}}', 'category_id', '{{%type_category}}', 'id',  'CASCADE', 'CASCADE');

        $this->batchInsert('{{%type_company}}', ['id', 'type', 'category_id'], [
            [1, Yii::t('app', 'Компания'), 1],
        ]);

        $this->createTable('{{%spec_company}}', [
            'id'            => $this->primaryKey()->comment(Yii::t('app', 'ID пользователя')),
            'type_id'       => $this->integer()->comment(Yii::t('app', 'Тип пользователя')),
            'company_id'    => $this->integer()->comment(Yii::t('app', 'Тип пользователя'))
        ], $tableOptions);

        $this->addForeignKey('spec_company_type', '{{%spec_company}}', 'type_id', '{{%type_company}}', 'id', 'CASCADE');
        $this->addForeignKey('spec_company', '{{%spec_company}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE');
    }

    public function down()
    {
        echo "m160913_135735_create_type_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
