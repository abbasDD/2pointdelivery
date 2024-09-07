--
-- Dumping data for table `moving_detail_categories`
--

INSERT INTO
    `moving_detail_categories` (
        `id`,
        `name`,
        `description`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'Test',
        NULL,
        '2024-05-29 01:13:36',
        '2024-05-29 01:13:36'
    ),
    (
        2,
        'Home',
        NULL,
        '2024-05-29 01:58:05',
        '2024-05-29 01:58:05'
    ),
    (
        3,
        'Living Room',
        NULL,
        '2024-05-29 09:39:29',
        '2024-05-29 09:39:29'
    );
--
-- Dumping data for table `moving_details`
--
INSERT INTO
    `moving_details` (
        `id`,
        `uuid`,
        `moving_detail_category_id`,
        `name`,
        `description`,
        `weight`,
        `volume`,
        `is_active`,
        `is_deleted`,
        `deleted_at`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        '950993',
        1,
        'Bike',
        'Test',
        '25',
        '15',
        1,
        0,
        NULL,
        '2024-05-29 01:46:02',
        '2024-05-29 01:46:02'
    ),
    (
        2,
        '736687',
        1,
        'Fridge',
        'This is for',
        '14',
        '22',
        1,
        0,
        NULL,
        '2024-05-29 03:36:02',
        '2024-05-29 03:36:02'
    ),
    (
        3,
        '158310',
        2,
        'Bed',
        'This is for single bed',
        '18',
        '45',
        1,
        0,
        NULL,
        '2024-05-29 03:55:23',
        '2024-05-29 03:55:23'
    ),
    (
        4,
        '295214',
        3,
        'Arm Chair',
        'rocking chair',
        '32',
        '70',
        1,
        0,
        NULL,
        '2024-05-29 09:40:54',
        '2024-05-29 09:40:54'
    ),
    (
        5,
        '89716214',
        3,
        'weight test',
        'new test Aug 30',
        '100',
        '3.53',
        1,
        0,
        NULL,
        '2024-08-29 20:56:40',
        '2024-08-31 11:05:50'
    );