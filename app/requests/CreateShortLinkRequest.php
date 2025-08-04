<?php

namespace app\requests;

use yii\base\Model;

/**
 * Класс CreateShortLinkRequest отвечает за валидацию входящих данных
 * при создании новой короткой ссылки через API.
 */
class CreateShortLinkRequest extends Model
{
    /**
     * URL, который пользователь хочет сократить.
     * Значение по умолчанию — пустая строка.
     */
    public string $url = '';

    /**
     * Правила валидации.
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['url'], 'required'],
            [['url'], 'url'],
        ];
    }
}
