--
-- Dumping data for table `smtp_settings`
--

INSERT INTO
    `smtp_settings` (
        `id`,
        `key`,
        `value`,
        `description`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'smtp_enabled',
        'yes',
        NULL,
        1,
        '2024-06-13 23:44:50',
        '2024-06-24 04:06:30'
    ),
    (
        2,
        'smtp_host',
        'mail.2pointdelivery.com',
        NULL,
        1,
        '2024-06-13 23:51:47',
        '2024-06-14 03:22:09'
    ),
    (
        3,
        'smtp_port',
        '465',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-06-14 03:22:09'
    ),
    (
        4,
        'smtp_username',
        'no-reply@2pointdelivery.com',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-08-19 13:52:47'
    ),
    (
        5,
        'smtp_password',
        'F.X(^^rG5H@Y',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-08-19 10:39:00'
    ),
    (
        6,
        'smtp_encryption',
        'SSL',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-06-14 03:22:09'
    ),
    (
        7,
        'smtp_from_email',
        'no-reply@2pointdelivery.com',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-08-19 13:52:47'
    ),
    (
        8,
        'smtp_from_name',
        '2Point Team',
        NULL,
        1,
        '2024-06-14 03:22:09',
        '2024-06-14 03:22:09'
    );