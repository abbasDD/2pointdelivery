--
-- Dumping data for table `email_templates`
--

INSERT INTO
    `email_templates` (
        `id`,
        `slug`,
        `name`,
        `subject`,
        `body`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'welcome-email',
        'Welcome Email',
        'Welcome to [Company name]',
        '<div>Dear [Customer],<br><br>Welcome to [Company name], and thank you for subscribing to our [services].<br>We’re very excited to have you on board and we are eager to assist in any way we can. If there’s anything we can do to improve your experience, please don’t hesitate to reach out.<br><br>Best regards,<br>[Your name]</div>',
        '2024-06-14 05:57:29',
        '2024-08-12 00:18:42'
    ),
    (
        2,
        'booking-status-email',
        'Booking Status Email',
        'Status of your order - Tracking ID [Tracking number]',
        '<div><strong>Dear [Customer name],<br></strong><br></div>\n\n<div>We are excited to inform you that your recent order for [Service category] has been shipped and is on its way to you. <br>Here is the tracking number you can use to monitor your shipment: [Tracking number].<br><br></div>\n\n<div>We are doing our best to help you receive your item as soon as possible. If you have any questions or concerns about the delivery, please don’t hesitate to contact us.<br><br></div>\n\n<div>Thank you for your business, and we hope you enjoy your new purchase.</div>\n\n<div><br><br></div>',
        '2024-08-19 13:41:23',
        '2024-08-19 13:42:07'
    );