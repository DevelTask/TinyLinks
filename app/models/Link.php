<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Модель Link представляет запись в таблице links.
 * Используется для хранения оригинального URL и его короткого кода.
 *
 * @property int $id
 * @property string $original_url Оригинальный URL, который был сокращён
 * @property string $short_code Уникальный короткий код
 * @property string $created_at Дата и время создания записи
 */
class Link extends ActiveRecord
{
    /**
     * Название таблицы в базе данных.
     */
    public static function tableName(): string
    {
        return 'links';
    }

    /**
     * Правила валидации модели.
     */
    public function rules(): array
    {
        return [
            [['original_url', 'short_code'], 'required'], // обязательные поля
            [['original_url'], 'string', 'max' => 2048],  // ограничение длины оригинального URL
            [['short_code'], 'string', 'max' => 5],      // ограничение длины короткого кода
            [['short_code'], 'unique'],                   // короткий код должен быть уникальным
        ];
    }

    /**
     * Автоматическое проставление даты создания.
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
