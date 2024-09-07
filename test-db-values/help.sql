--
-- Dumping data for table `help_topics`
--

INSERT INTO
    `help_topics` (
        `id`,
        `uuid`,
        `name`,
        `content`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'EEICfjst',
        'New Cateogry',
        NULL,
        1,
        '2024-06-04 12:06:49',
        '2024-06-04 12:06:49'
    ),
    (
        2,
        'rCr0jZKH',
        'Getting Started',
        'Walk through the basics of setting up your account',
        1,
        '2024-06-04 15:30:27',
        '2024-06-04 15:30:27'
    ),
    (
        3,
        '1e6A2cmt',
        'Account',
        'Learn to manage your account and team members',
        1,
        '2024-06-04 15:31:07',
        '2024-06-04 15:31:07'
    ),
    (
        4,
        'Dsnmj80A',
        'Billing',
        'Learn how to pay for your services',
        1,
        '2024-06-04 15:31:58',
        '2024-06-04 15:31:58'
    ),
    (
        5,
        'I5hnEaks',
        'Services',
        'Get to know the various services we offer.',
        1,
        '2024-06-04 15:53:29',
        '2024-06-04 15:53:29'
    ),
    (
        6,
        'Gcr2psxJ',
        'Pricing',
        'Understand our pricing processes.',
        1,
        '2024-06-04 15:54:20',
        '2024-06-04 15:54:20'
    ),
    (
        7,
        'PctLfTsb',
        'Policies and Procedures',
        'Policies and Procedures',
        1,
        '2024-07-06 00:47:47',
        '2024-07-06 00:47:47'
    );

--
-- Dumping data for table `help_questions`
--

INSERT INTO
    `help_questions` (
        `id`,
        `uuid`,
        `help_topic_id`,
        `question`,
        `answer`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'qhTc67rh',
        2,
        'Overview of the 2Point App',
        'Welcome to the 2Point App, your go-to platform for on-demand delivery, moving, and cleaning services. The 2Point App connects users with reliable Helpers who can assist with various tasks,',
        1,
        '2024-06-04 12:10:54',
        '2024-06-13 03:22:52'
    ),
    (
        2,
        '1JFeLTcZ',
        2,
        'Creating an Account',
        'To get started with the 2Point App, you need to create an account. Here\'s how:\r\nCongratulations! You\'ve successfully created your 2Point account. You can now start using the app to book services and manage your tasks.',
        1,
        '2024-06-13 03:24:05',
        '2024-06-13 03:24:05'
    ),
    (
        3,
        '0rfTa9ya',
        5,
        'test question',
        '<div><strong>2Point Delivery Ltd. Comprehensive Driver Guidelines<br></strong><br></div><div><strong>Introduction<br></strong>At 2Point Delivery, our commitment to excellence begins with our Helpers, particularly our drivers. We aim to provide exceptional service by ensuring our drivers adhere to the highest standards of professionalism, safety, competence, and customer service. These comprehensive guidelines incorporate industry best practices and additional topics to help our drivers consistently meet and exceed these expectations.</div><div><strong>1. Professionalism</strong></div><ul><li><strong>Appearance and Uniform:</strong> Maintain a clean and tidy appearance at all times. Wear the 2Point uniform if provided or adhere to a business-casual dress code. Personal hygiene is crucial; ensure you are well-groomed.</li><li><strong>Punctuality and Reliability:</strong> Always arrive on time for scheduled pickups and deliveries. Update the 2Point app with accurate ETA information and notify customers if there are any delays.</li><li><strong>Communication:</strong> Use polite, professional, and clear language in all interactions with customers and colleagues. Maintain a positive attitude and be courteous even in challenging situations.</li><li><strong>Identification and Documentation:</strong> Carry a valid ID and display the 2Point badge, if applicable. Ensure all necessary delivery documentation is completed accurately and promptly.<br><br></li></ul><div><strong><br>2. Safety</strong></div><ul><li><strong>Vehicle Maintenance and Safety Checks:</strong> Conduct daily safety checks on your vehicle, including brakes, tires, lights, and fluid levels. Report any issues immediately and do not operate an unsafe vehicle.</li><li><strong>Defensive Driving:</strong> Adhere to all traffic laws and regulations. Practice defensive driving techniques to anticipate potential hazards and avoid accidents. Avoid distractions, including mobile devices, unless using hands-free options.</li><li><strong>Load Security and Handling:</strong> Properly secure all items in the vehicle to prevent damage during transit. Use appropriate equipment, such as straps and padding, to ensure the load is stable and protected.</li><li><strong>Emergency Preparedness and Response:</strong> Be familiar with emergency procedures and carry a basic first-aid kit in your vehicle. Know how to contact emergency services and the 2Point Delivery support team in case of an accident or other emergencies.</li><li><strong>Security Consciousness:</strong> Be aware of your surroundings at all times. Stay vigilant to potential security risks and report any suspicious activities to the appropriate authorities and the 2Point support team.<br><br></li></ul><div><br></div><div><strong><br>3. Competence</strong></div><ul><li><strong>Training and Certification:</strong> Complete all required training sessions provided by 2Point Delivery, including updates and refresher courses. Obtain and maintain any necessary certifications for specialized deliveries such as Cannabis and medical supply delivery.</li><li><strong>Technology Proficiency:</strong> Be proficient in using the 2Point Delivery app for job notifications, navigation, and customer communication. Regularly update the app and familiarize yourself with new features.</li><li><strong>Problem-Solving and Decision-Making:</strong> Develop the ability to handle common delivery issues, such as incorrect addresses or unavailable recipients, effectively and efficiently. Use sound judgment in unexpected situations and escalate problems to the appropriate support team member when necessary.</li><li><strong>Continuous Improvement:</strong> Actively seek and respond to feedback from customers and supervisors to improve service quality. Participate in ongoing training and professional development opportunities.<br><br></li></ul><div><strong><br>4. Customer Service<br></strong><br></div><ul><li><strong><br>Courtesy and Respect:</strong> Greet customers with a smile and a friendly attitude. Show respect and consideration at all times, even in difficult situations.</li><li><strong>Handling and Presentation:</strong> Treat all packages with care. Ensure that fragile items are handled with extra caution and clearly communicate any special handling requirements to customers. Deliver packages in a presentable condition.</li><li><strong>Issue Resolution and Follow-Up:</strong> Address any customer complaints or issues promptly and professionally. If unable to resolve a problem, escalate it to the appropriate support team member. After completing a delivery, confirm with the customer that they are satisfied with the service and offer assistance with any immediate needs or questions.</li><li><strong>Customer Feedback:</strong> Encourage customers to provide feedback on their experience. Use this feedback constructively to enhance your service and address any areas for improvement.<br><br></li></ul><div><strong><br>5. Delivery Process<br></strong><br></div><ul><li><strong><br>Pre-Delivery Preparation:</strong> Verify the delivery address and special instructions through the 2Point app. Ensure all items for delivery are correctly loaded and securely fastened.</li><li><strong>En Route Communication:</strong> Keep the customer informed about the delivery status, including any potential delays, through the app or direct communication if necessary.</li><li><strong>Arrival at Destination:</strong> Upon arrival, park your vehicle in a safe and legal spot. Do not block driveways or entrances.</li><li><strong>Transaction Outside the Home:</strong> Always conduct transactions outside the customer\'s premises. Never enter the customer’s home or private property. Ensure the customer receives and inspects their package before leaving the site.</li><li><strong>Delivery Confirmation:</strong> Confirm the delivery through the app by obtaining the customer’s signature or following the specific confirmation process outlined in the app.<br><br></li></ul><div><strong><br>6. Personal Healthcare Protection<br></strong><br></div><ul><li><strong><br>Health Insurance: </strong>Ensure you have your own health insurance coverage. This is essential to protect yourself in case of illness or injury.</li><li><strong>Health and Safety Protocols:</strong> Follow all health and safety protocols, including wearing appropriate personal protective equipment (PPE) such as masks and gloves, especially when handling packages or interacting with customers.</li><li><strong>Hygiene Practices:</strong> Regularly wash your hands with soap and water or use hand sanitizer, particularly before and after deliveries. Keep hand sanitizer and disinfectant wipes in your vehicle.</li><li><strong>Health Monitoring:</strong> Monitor your health regularly. If you experience any symptoms of illness, report them immediately to the 2Point support team and refrain from working until you are cleared by a healthcare professional.</li><li><strong>Mental Well-being:</strong> Take care of your mental health. If you feel stressed or overwhelmed, seek support from the 2Point team or a mental health professional.<br><br></li></ul><div><strong><br>7. Additional Guidelines<br></strong><br></div><ul><li><strong><br>Confidentiality and Data Protection:</strong> Respect customer privacy and confidentiality. Do not share customer information with unauthorized individuals. Follow all data protection regulations and company policies.</li><li><strong>Environmental Responsibility:</strong> Minimize your environmental impact by reducing idling time, maintaining your vehicle for optimal fuel efficiency, and properly disposing of any waste.</li><li><strong>Fitness for Duty:</strong> Ensure you are fit to drive and do not operate a vehicle under the influence of alcohol, drugs, or when excessively fatigued.<br><br></li></ul><div><strong><br>Summary<br></strong><br></div><div><br>By adhering to these comprehensive guidelines, drivers at 2Point Delivery Ltd. will ensure their safety, professionalism, and competency while enhancing the overall customer experience. Upholding these standards is essential to maintaining our reputation as a reliable, customer-centric, and people-centered service provider.<br><br></div><div><br>For any questions or further clarification on these guidelines, please contact the 2Point support team at info@2pointdelivery.com or call 639-997-2710.<br><br></div><div><br>Thank you for your dedication to excellence and for being a valued member of the 2Point team.<br><br></div><div><br><br></div>',
        1,
        '2024-06-25 06:19:05',
        '2024-06-25 06:19:05'
    ),
    (
        4,
        '0rRU1ujR',
        7,
        'Insurance and Liability',
        '<div>As an AI, I don’t have access to specific pricing details or information on individual software, apps, or websites used by companies like 2Point Delivery Limited for logistics management. The presence of maintenance or yearly fees for logistics management software, apps, or websites can vary based on several factors:<br><br></div><ol><li><strong>Software Provider:</strong> Different software providers may have varying pricing models. Some may charge a one-time fee, while others opt for subscription-based models with monthly or yearly fees.<br><br></li><li><strong>Features and Services:</strong> The presence of maintenance or yearly fees might depend on the features and services included in the software package. Additional functionalities, updates, or customer support services might incur extra costs.<br><br></li><li><strong>Customization and Integration:</strong> Fees may also be influenced by customization options or integration with other systems, especially if tailored solutions or extensive integrations are required.<br><br></li><li><strong>Usage and Scalability:</strong> Some software providers may adjust their fees based on usage metrics or scalability requirements as the company grows or expands its operations.<br><br></li></ol><div>To obtain specific information regarding maintenance or yearly fees for logistics management software, apps, or websites, it\'s recommended to directly contact the software providers or developers. They can provide detailed pricing structures, including any recurring fees for maintenance, updates, or additional services associated with their products.<br><br></div><div>3.5</div><div><br></div><div>When will you require payment for your services? Is it before or after delivery? What method of payments will you accept? go straight</div><div><br></div><div>ChatGPT</div><div>Payment for our services at 2Point Delivery Limited is typically required before the delivery. We accept various methods of payment, including credit/debit cards, electronic bank transfers, online payment platforms, and cash transactions. The specific payment method and terms can be discussed and agreed upon during the booking or confirmation process with our customers.<br><br></div>',
        1,
        '2024-07-06 00:50:20',
        '2024-07-06 00:50:20'
    );