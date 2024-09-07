--
-- Dumping data for table `tax_settings`
--

INSERT INTO
    `tax_settings` (
        `id`,
        `country_id`,
        `state_id`,
        `city_id`,
        `gst_rate`,
        `pst_rate`,
        `hst_rate`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        1,
        3875,
        NULL,
        '25.00',
        '25',
        '25',
        1,
        '2024-05-14 06:57:28',
        '2024-05-14 07:12:45'
    ),
    (
        2,
        1,
        3870,
        NULL,
        '15',
        '15',
        '15',
        1,
        '2024-05-15 08:51:37',
        '2024-05-15 08:51:37'
    ),
    (
        3,
        39,
        870,
        NULL,
        '5',
        '6',
        '0',
        1,
        '2024-05-19 20:43:18',
        '2024-05-19 20:43:18'
    );