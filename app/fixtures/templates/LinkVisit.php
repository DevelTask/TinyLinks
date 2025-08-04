<?php

$visitedAt = $faker->dateTimeBetween('2025-05-01', '2025-08-04');
return [
    'link_id' => $faker->numberBetween(1, 30), // подстрой под фактическое количество ссылок
    'visited_at' => $visitedAt->format('Y-m-d H:i:s'),
    'year' => (int)$visitedAt->format('Y'),
    'month' => (int)$visitedAt->format('n'),
    'user_agent' => $faker->userAgent,
    'ip_address' => $faker->ipv4,
];
