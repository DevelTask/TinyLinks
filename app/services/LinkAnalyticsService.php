<?php

namespace app\services;

use Yii;

class LinkAnalyticsService
{
    /**
     * Возвращает фиксированный аналитический отчёт по ссылкам за май–август 2025 года.
     *
     * @return array
     */
    public function getHardcodedReport(): array
    {
        $sql = "
            WITH monthly_counts AS (
                SELECT
                    CONCAT(lv.year, '-', LPAD(lv.month, 2, '0')) AS month,
                    l.original_url AS url,
                    COUNT(*) AS visits
                FROM link_visits lv
                JOIN links l ON lv.link_id = l.id
                WHERE lv.year = 2025 AND lv.month BETWEEN 5 AND 8
                GROUP BY month, url
            ),
            ranked AS (
                SELECT
                    month,
                    url,
                    visits,
                    RANK() OVER (PARTITION BY month ORDER BY visits DESC) AS position
                FROM monthly_counts
            )
            SELECT * FROM ranked
            ORDER BY month DESC, position ASC;
        ";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}
