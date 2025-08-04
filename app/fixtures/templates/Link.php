<?php

return [
    'short_code' => $faker->regexify('[A-Za-z0-9]{5}'),
    'original_url' => $faker->url,
    'created_at' => $faker->dateTimeBetween('2025-05-01', '2025-08-04')->format('Y-m-d H:i:s'),
];
