--
-- Dumping data for table `social_login_settings`
--

INSERT INTO
    `social_login_settings` (
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
        'google_enabled',
        'yes',
        NULL,
        1,
        '2024-06-07 05:54:04',
        '2024-06-20 04:17:18'
    ),
    (
        2,
        'facebook_enabled',
        'no',
        NULL,
        1,
        '2024-06-07 05:54:04',
        '2024-07-03 01:48:08'
    ),
    (
        3,
        'google_client_id',
        '1021412260538-t6qig5udrafamufier8083q9tdmtfj2p.apps.googleusercontent.com',
        NULL,
        1,
        '2024-06-20 04:17:18',
        '2024-08-27 18:32:55'
    ),
    (
        4,
        'google_secret_id',
        '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',
        NULL,
        1,
        '2024-06-20 04:17:18',
        '2024-08-27 18:32:55'
    ),
    (
        5,
        'google_redirect_uri',
        'https://sandbox.2pointdelivery.com/auth/google/callback',
        NULL,
        1,
        '2024-06-20 04:17:18',
        '2024-08-27 18:30:36'
    );