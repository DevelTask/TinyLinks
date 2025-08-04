<?php

namespace app\repositories;

use app\models\LinkVisit;

class LinkVisitRepository
{
    public function create(array $attributes): LinkVisit
    {
        return new LinkVisit($attributes);
    }

    /**
     * Сохраняет объект перехода по ссылке.
     *
     * @throws \RuntimeException
     */
    public function save(LinkVisit $visit): void
    {
        if (!$visit->save()) {
            throw new \RuntimeException('Error visitor : ' . json_encode($visit->getErrors()));
        }
    }
}
