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
        'Standard',
        'standard',
        '0',
        1,
        0,
        '2024-05-13 15:33:19',
        '2024-05-15 17:37:38'
    ),
    (
        2,
        'delivery',
        'Express',
        'urgent requests',
        '2',
        1,
        0,
        '2024-05-13 15:33:19',
        '2024-06-03 22:36:39'
    ),
    (
        3,
        'moving',
        'Standard',
        '3-5 day notice',
        '0',
        1,
        0,
        '2024-06-03 21:24:24',
        '2024-06-03 21:24:24'
    ),
    (
        4,
        'moving',
        'Sameday',
        'Sameday notice',
        '10',
        1,
        0,
        '2024-06-03 21:24:52',
        '2024-06-03 21:24:52'
    ),
    (
        5,
        'moving',
        'Express',
        'immediate notice',
        '25',
        1,
        0,
        '2024-06-03 21:25:13',
        '2024-06-03 21:25:13'
    );