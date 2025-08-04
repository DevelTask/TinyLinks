<?php

namespace app\models;

use yii\db\ActiveRecord;

class LinkVisit extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'link_visits';
    }

    public function rules(): array
    {
        return [
            [['link_id', 'visited_at', 'year', 'month'], 'required'],
            [['user_agent'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
            [['link_id', 'year', 'month'], 'integer'],
            [['visited_at'], 'safe'],
        ];
    }
}
