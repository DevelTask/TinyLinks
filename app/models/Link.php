<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Link extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'links';
    }

    public function rules(): array
    {
        return [
            [['original_url', 'short_code'], 'required'],
            [['original_url'], 'string', 'max' => 2048],
            [['short_code'], 'string', 'max' => 10],
            [['short_code'], 'unique'],
        ];
    }

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
