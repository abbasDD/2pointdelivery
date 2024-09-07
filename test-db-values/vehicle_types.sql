--
-- Dumping data for table `vehicle_types`
--

INSERT INTO
    `vehicle_types` (
        `id`,
        `uuid`,
        `name`,
        `description`,
        `image`,
        `price_type`,
        `price`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        '7wJJfyi35yuX65xN6SOlFXllgFuStNDA',
        'Bike',
        'This is for bike service',
        '1715614142.png',
        'km',
        '0',
        1,
        '2024-05-13 10:29:02',
        '2024-06-12 22:25:16'
    ),
    (
        2,
        'tgczG2KknWL4tw1NRd9yInD9NfQzQTyg',
        'Car',
        'Car/SUV',
        '1724122532.png',
        'km',
        '0',
        1,
        '2024-05-14 00:13:31',
        '2024-08-19 16:55:32'
    ),
    (
        3,
        'JooulPjnKx8RgnRmNsckn0y7OEZY7rti',
        'Mini-Van',
        'Trailer truck',
        '1724122572.png',
        'km',
        '3.5',
        1,
        '2024-06-01 20:26:10',
        '2024-08-19 16:56:12'
    ),
    (
        4,
        'rRN5MaCYeZjX2JjC1UL5c0h6o00fT63l',
        'Box Truck \'16',
        'Box Truck \'16',
        '1724122658.png',
        'km',
        '15',
        1,
        '2024-08-19 16:57:38',
        '2024-08-19 16:57:38'
    ),
    (
        5,
        'VlMw2girDcqCZskSPEjNdRAxGjX05cjv',
        'Box Truck \'20',
        'Box Truck \'20',
        '1724122712.png',
        'km',
        '45',
        1,
        '2024-08-19 16:58:32',
        '2024-08-19 16:58:32'
    ),
    (
        6,
        'fhSFzO6SzjIoBUwT3MBwLIGAm6YZN2gN',
        'U-Haul',
        'U-Haul',
        '1724122755.png',
        'km',
        '0',
        1,
        '2024-08-19 16:59:15',
        '2024-08-19 16:59:15'
    );