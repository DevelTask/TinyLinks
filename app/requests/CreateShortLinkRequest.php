<?php

namespace app\requests;

use yii\base\Model;

class CreateShortLinkRequest extends Model
{
    public string $url = '';

    public function rules(): array
    {
        return [
            [['url'], 'required'],
            [['url'], 'url'],
        ];
    }
}
