<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель LinkVisit представляет собой запись о посещении короткой ссылки.
 *
 * @property int $link_id ID связанной ссылки
 * @property string $visited_at Дата и время визита
 * @property int $year Год визита (используется для партиционирования)
 * @property int $month Месяц визита (используется для партиционирования)
 * @property string|null $user_agent Строка User-Agent клиента
 * @property string|null $ip_address IP-адрес клиента
 */
class LinkVisit extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в БД.
     */
    public static function tableName(): string
    {
        return 'link_visits';
    }

    /**
     * Правила валидации для модели.
     */
    public function rules(): array
    {
        return [
            [['link_id', 'visited_at', 'year', 'month'], 'required'],
            [['link_id', 'year', 'month'], 'integer'],
            [['visited_at'], 'safe'],
            [['user_agent'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }
}
