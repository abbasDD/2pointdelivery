INSERT INTO
    `priority_settings` (
        `id`,
        `type`,
        `name`,
        `description`,
        `price`,
        `is_active`,
        `is_deleted`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'delivery',
        'Same Day',
        'Same Day',
        '0',
        1,
        0,
        '2024-05-13 15:33:19',
        '2024-08-19 17:19:29'
    ),
    (
        2,
        'delivery',
        'Express',
        'Express',
        '10',
        1,
        0,
        '2024-05-13 15:33:19',
        '2024-08-19 17:19:45'
    ),
    (
        3,
        'moving',
        'Standard',
        'Within 24hrs',
        '0',
        1,
        0,
        '2024-06-01 05:23:39',
        '2024-06-01 05:23:39'
    ),
    (
        4,
        'moving',
        'Express',
        'immediately',
        '40',
        1,
        0,
        '2024-06-01 05:24:07',
        '2024-06-01 05:24:07'
    ),
    (
        5,
        'delivery',
        'Overnight',
        'Overnight',
        '0',
        1,
        0,
        '2024-08-19 17:20:14',
        '2024-08-19 17:20:14'
    );