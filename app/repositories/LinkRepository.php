<?php

namespace app\repositories;

use app\models\Link;
use yii\db\ActiveRecordInterface;
use yii\db\Exception;

class LinkRepository
{
    /**
     * Сохранение ссылки.
     *
     * @throws \RuntimeException если сохранение не удалось
     */
    public function save(Link $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException('Failed to save the link: ' . json_encode($model->getErrors()));
        }
    }

    /**
     * Поиск ссылку по short_code
     */
    public function findByShortCode(string $code): ?Link
    {
        return Link::find()->where(['short_code' => $code])->one();
    }

    /**
     * Поиск по оригинальной ссылке
     */
    public function findByOriginalUrl(string $originalUrl): ?Link
    {
        return Link::find()->where(['original_url' => $originalUrl])->one();
    }

    /**
     * Проверка, существует ли ссылка по short_code
     */
    public function existsByShortCode(string $code): bool
    {
        return Link::find()->where(['short_code' => $code])->exists();
    }
}
