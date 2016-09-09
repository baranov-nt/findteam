<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m000000_000000_create_user_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                    => $this->primaryKey()->comment('ID пользователя'),
            'alias'                 => $this->string()->unique()->comment('Уникальная ссылка'),
            'username'              => $this->string(32)->unique()->comment('Логин'),
            'email'                 => $this->string(64)->unique()->comment('Электронная почта'),
            'phone'                 => $this->string(11)->unique()->comment('Телефон'),
            'full_phone'            => $this->string(15)->unique()->comment('Полный номер'),
            'description'           => $this->text()->comment('Описание'),
            'status'                => $this->smallInteger(1)->notNull()->comment('Статус'),
            'image_main'            => $this->string(20)->defaultValue('mainUser')->comment('Метка изображения'),
            'images'                => $this->string(20)->defaultValue('imagesUser')->comment('Метка изображения доп фото'),
            'password_hash'         => $this->string()->comment('Пароль'),
            'auth_key'              => $this->string(32)->comment('Ключ авторизации'),
            'password_reset_token'  => $this->string()->comment('Ключ сброса пароля'),
            'email_confirm_token'   => $this->string()->comment('Ключ подтверждения эл. адреса'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        /* Пользователи онлайн */
        $this->createTable('{{%user_online}}', [
            'user_id'           => $this->primaryKey(),
            'online'            => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('online_user_fk', '{{%user_online}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        /* Профиль компании */
        $this->createTable('{{%profile_company}}', [
            'id'                    => $this->primaryKey()->comment('ID компании'),
            'type'                  => $this->smallInteger(1)->comment('Тип компании'),
            'tariff'                => $this->string(20)->comment('Тариф'),
            'status'                => $this->smallInteger(1)->notNull()->comment('Статус'),
            'name'                  => $this->string()->unique()->comment('Название компании'),
            'description'           => $this->text()->comment('Описание'),
            'image_main'            => $this->string(20)->defaultValue('mainCompany')->comment('Метка изображения'),
            'images'                => $this->string(20)->defaultValue('imagesCompany')->comment('Метка изображения доп фото'),
            'inn'                   => $this->string(18)->comment('ИНН'),
            'ogrn'                  => $this->string(20)->comment('ОГРН'),
            'okpo'                  => $this->string(15)->comment('ОКПО'),
            'okved'                 => $this->string(12)->comment('ОКВЭД'),
            'okato'                 => $this->string(18)->comment('ОКАТО'),
            'bik'                   => $this->string(10)->comment('БИК'),
            'kpp'                   => $this->string(10)->comment('КПП'),
        ], $tableOptions);

        /* Профиль пользователя */
        $this->createTable('{{%profile_user}}', [
            'id'                    => $this->primaryKey()->comment('ID пользователя'),
            'type'                  => $this->smallInteger(1)->notNull()->comment('Тип пользователя'),         // например (сантехник, бухгалтер, модель), а роли определяют тип аккаунта
                                                                                                                // например (администратор сайта, редактор сайта, владелец компании, пользователь)
            'tariff'                => $this->string(20)->comment('Тариф'),
            'first_name'            => $this->string(32)->comment('Имя'),
            'last_name'             => $this->string(32)->comment('Фамилия'),
            'middle_name'           => $this->string(32)->comment('Отчество'),
            'sex'                   => $this->smallInteger(1)->comment('Пол'),
            'day_birth'             => $this->smallInteger(2)->comment('День рождения'),
            'month_birth'           => $this->smallInteger(2)->comment('Месяц рождения'),
            'year_birth'            => $this->smallInteger(4)->comment('Год рождения'),
            'age'                   => $this->smallInteger(3)->comment('Возраст'),
            'social_status'         => $this->boolean()->comment('Социальный статус'),              // не в браке - false, в браке - true
            'children'              => $this->boolean()->comment('Дети'),                           // нет - false, есть - true
            'children_count'        => $this->smallInteger(2)->comment('Количество детей'),
            'inn'                   => $this->string(18)->comment('ИНН'),
            'company_id'            => $this->integer()->comment('Компания'),
        ], $tableOptions);

        $this->addForeignKey('usermain_fk', '{{%profile_user}}', 'id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('company_fk', '{{%profile_user}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE', 'CASCADE');     // компания пользователя

        /* Адреса к пользователям (много ко многим) */
        $this->createTable('{{%address}}', [
            'id'                    => $this->primaryKey()->comment('ID адреса'),
            'type'                  => $this->smallInteger(1)->notNull()->comment('Тип адреса'),    // 0 - жительства, 1 - почт, 2 - юр, 3 - офис, 4 - склад, 5 - демонстрационная комната
            'image_main'            => $this->string(20)->defaultValue('mainAddress')->comment('Метка изображения'),
            'images'                => $this->string(20)->defaultValue('imagesAddress')->comment('Метка изображения доп фото'),
            'index'                 => $this->string()->comment('Индекс'),
            'address'               => $this->string()->comment('Адрес'),
            'city_id'               => $this->integer()->comment('Город'),
            'country_id'            => $this->integer()->comment('Страна'),
            'user_id'               => $this->integer()->comment('ID пользователя'),
            'company_id'            => $this->integer()->comment('ID rомпании'),
            'main'                  => $this->boolean()->defaultValue(false)->comment('Основной'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        $this->addForeignKey('address_city_fk', '{{%address}}', 'city_id', '{{%geo_city}}', 'id', 'CASCADE');
        $this->addForeignKey('address_country_fk', '{{%address}}', 'country_id', '{{%geo_country}}', 'id', 'CASCADE');
        $this->addForeignKey('address_user_fk', '{{%address}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('address_company_fk', '{{%address}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE', 'CASCADE');

        /* Расписание адреса */
        $this->createTable('{{%schedule}}', [
            'id'                    => $this->primaryKey()->comment('ID расписания'),
            'type'                  => $this->smallInteger(1)->notNull()->comment('Тип расписания'),
            'day'                   => $this->smallInteger(1)->notNull()->comment('Рабочий день'),              // 0 - все, 1-7 дни недели пн-вс
            'start_time'            => $this->integer()->comment('Время открытия'),
            'end_time'              => $this->integer()->comment('Время закрытия'),
            'address_id'            => $this->integer()->unique()->comment('Адрес'),                            // если активно, время работы адреса
            'user_id'               => $this->integer()->comment('ID пользователя'),
            'company_id'            => $this->integer()->comment('ID rомпании'),
            'main'                  => $this->boolean()->defaultValue(false)->comment('Основной'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        $this->addForeignKey('schedule_address_fk', '{{%schedule}}', 'address_id', '{{%address}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('schedule_user_fk', '{{%schedule}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('schedule_company_fk', '{{%schedule}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE', 'CASCADE');

        /* Дополнительные контакты к пользователям (много ко многим) */
        $this->createTable('{{%contact}}', [
            'id'                    => $this->primaryKey()->comment('ID адреса'),
            'type'                  => $this->smallInteger(1)->notNull()->comment('Тип контакта'),              // 0 - телефон, 1 - email, 2 - skype, 3 - whatsapp, 4 - viber, 5 - почта
            'contact'               => $this->string()->comment('Контакт'),
            'address_id'            => $this->integer()->unique()->comment('Адрес'),                            // если активно, время работы адреса
            'user_id'               => $this->integer()->comment('ID пользователя'),
            'company_id'            => $this->integer()->comment('ID rомпании'),
            'main'                  => $this->boolean()->defaultValue(false)->comment('Основной'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        $this->addForeignKey('contact_address_fk', '{{%contact}}', 'address_id', '{{%address}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('contact_user_fk', '{{%contact}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('contact_company_fk', '{{%contact}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE', 'CASCADE');

        /* Банковские реквизиты, например для платежей (много ко многим) */
        $this->createTable('{{%bank}}', [
            'id'                    => $this->primaryKey()->comment('ID банка'),
            'owner'                 => $this->smallInteger(1)->notNull()->comment('Владелец'),                  // 0 - пользователь, 1 - компания
            'name'                  => $this->string()->unique()->comment('Название банка'),
            'phone'                 => $this->string(11)->comment('Телефон'),
            'inn'                   => $this->string(18)->comment('ИНН'),
            'ogrn'                  => $this->string(20)->comment('ОГРН'),
            'okpo'                  => $this->string(15)->comment('ОКПО'),
            'okved'                 => $this->string(12)->comment('ОКВЭД'),
            'okato'                 => $this->string(18)->comment('ОКАТО'),
            'checking_account'      => $this->string(22)->comment('Расчетный счет'),
            'correspondent_account' => $this->string(22)->comment('Корреспондентский счет'),
            'bik'                   => $this->string(10)->comment('БИК'),
            'kpp'                   => $this->string(10)->comment('КПП'),
            'address_id'            => $this->integer()->comment('Адрес'),
            'user_id'               => $this->integer()->comment('ID пользователя'),
            'company_id'            => $this->integer()->comment('ID rомпании'),
            'main'                  => $this->boolean()->defaultValue(false)->comment('Основной'),
            'created_at'            => $this->integer()->comment('Дата создания'),
            'updated_at'            => $this->integer()->comment('Дата изменения')
        ], $tableOptions);

        $this->addForeignKey('bank_address_fk', '{{%bank}}', 'address_id', '{{%address}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('bank_user_fk', '{{%bank}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('bank_company_fk', '{{%bank}}', 'company_id', '{{%profile_company}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
