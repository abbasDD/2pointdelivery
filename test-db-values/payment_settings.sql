--
-- Dumping data for table `payment_settings`
--

INSERT INTO
    `payment_settings` (
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
        'paypal_client_id',
        'ASEY0qzn6aXTfFyXaaeENRXemr7QjnkRI1uagaibw0AmtYvLUhWLx9ksOVL7COH8AaJKrIW9TEuyxHCW',
        NULL,
        1,
        '2024-06-02 05:18:30',
        '2024-06-02 05:18:30'
    ),
    (
        2,
        'paypal_secret_id',
        'EBULrRY_tcpaVa7h2NVTjzcvDA8lISwjoQHwT61NK_lyyE64BibdViDdXdZbWKsvxFkxaZ_QRKN_LHDR',
        NULL,
        1,
        '2024-06-02 05:18:30',
        '2024-06-02 05:18:30'
    ),
    (
        3,
        'stripe_publishable_key',
        'pk_test_51NCurSJ8eezyeqzyHxvgn8v69vMyxZe5zrEvwamX3c5aeP7NiMiCfigpmGZSUf64llZYc0UC6TJKd9CbEmNUSJHT00qjlBDjSv',
        NULL,
        1,
        '2024-06-02 05:18:30',
        '2024-08-27 16:42:17'
    ),
    (
        4,
        'stripe_secret_key',
        'sk_test_51NCurSJ8eezyeqzyxvmLtLlBmVvSR9iD6WNGGKvY8Eo5eD2cBGdwycMxktkeDl6w7HhkqgER1GP49pBaqPYkfE1X00wIopGkgA',
        NULL,
        1,
        '2024-06-02 05:18:30',
        '2024-08-27 16:42:17'
    ),
    (
        5,
        'cod_enabled',
        'yes',
        NULL,
        1,
        '2024-06-07 03:59:27',
        '2024-06-21 20:41:19'
    ),
    (
        6,
        'paypal_enabled',
        'yes',
        NULL,
        1,
        '2024-06-07 03:59:27',
        '2024-07-23 07:22:46'
    ),
    (
        7,
        'stripe_enabled',
        'yes',
        NULL,
        1,
        '2024-06-07 03:59:27',
        '2024-07-23 07:19:12'
    );