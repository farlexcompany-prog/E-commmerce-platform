<?php

return [
    'pages' => [
        'site' => [
            'title' => 'Shared header & footer',
            'url' => 'Every page',
            'group' => 'Global',
            'description' => 'Edit the announcement bar, navigation labels, and shared footer copy.',
            'sections' => [
                'announcement' => [
                    'title' => 'Announcement bar',
                    'order' => 10,
                    'fields' => [
                        'delivery' => ['label' => 'Delivery message', 'type' => 'text', 'default' => 'Free delivery above ₹499'],
                        'prescription' => ['label' => 'Prescription message', 'type' => 'text', 'default' => 'Prescription checks for Rx products'],
                        'coupon' => ['label' => 'Offer message', 'type' => 'text', 'default' => 'Use code FIRST10'],
                        'link_text' => ['label' => 'Link text', 'type' => 'text', 'default' => 'Explore the demo range'],
                    ],
                ],
                'navigation' => [
                    'title' => 'Main navigation',
                    'order' => 20,
                    'fields' => [
                        'medicines' => ['label' => 'Medicines link', 'type' => 'text', 'default' => 'Medicines'],
                        'devices' => ['label' => 'Healthcare devices link', 'type' => 'text', 'default' => 'Healthcare Devices'],
                        'supplements' => ['label' => 'Vitamins & supplements link', 'type' => 'text', 'default' => 'Vitamins & Supplements'],
                        'diabetes' => ['label' => 'Diabetes care link', 'type' => 'text', 'default' => 'Diabetes Care'],
                        'more' => ['label' => 'More categories label', 'type' => 'text', 'default' => 'More categories'],
                        'offers' => ['label' => 'Offers link', 'type' => 'text', 'default' => 'Offers'],
                    ],
                ],
                'footer' => [
                    'title' => 'Footer',
                    'order' => 30,
                    'fields' => [
                        'intro' => ['label' => 'Brand introduction', 'type' => 'textarea', 'default' => 'Demo storefront. Product, regulatory, contact and policy details must be reviewed before launch.'],
                        'explore_heading' => ['label' => 'Explore heading', 'type' => 'text', 'default' => 'Explore'],
                        'help_heading' => ['label' => 'Help & policies heading', 'type' => 'text', 'default' => 'Help & policies'],
                        'contact_heading' => ['label' => 'Contact heading', 'type' => 'text', 'default' => 'Contact & compliance'],
                        'disclaimer' => ['label' => 'Footer disclaimer', 'type' => 'textarea', 'default' => 'Medicines should be taken only as directed by a registered medical practitioner. Demo catalogue copy and prices are placeholders; prescription review, customer accounts and payment processing are not live.'],
                    ],
                ],
            ],
        ],
        'home' => [
            'title' => 'Home page',
            'url' => '/',
            'group' => 'Storefront',
            'reorderable' => true,
            'description' => 'Manage the homepage banners, promotions, and content sections.',
            'sections' => [
                'banner_one' => [
                    'title' => 'Hero banner 1',
                    'order' => 10,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'A better way to shop health essentials'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Care for every day.'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Explore a considered range of health and wellness products, with clear information at every step.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Shop the collection'],
                        'button_url' => ['label' => 'Button URL', 'type' => 'url', 'default' => '/shop'],
                        'image' => ['label' => 'Banner image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'banner_two' => [
                    'title' => 'Hero banner 2',
                    'order' => 20,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'For products marked Rx'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Prescription first. Always.'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Add your prescription at checkout so a qualified pharmacy team can review it before fulfilment.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Browse Rx products'],
                        'button_url' => ['label' => 'Button URL', 'type' => 'url', 'default' => '/shop?rx=rx'],
                        'image' => ['label' => 'Banner image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'banner_three' => [
                    'title' => 'Hero banner 3',
                    'order' => 30,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'A little welcome'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => '10% off your first order.'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Use FIRST10 at checkout. Sample offer terms; confirm eligibility and local rules before launch.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Explore offers'],
                        'button_url' => ['label' => 'Button URL', 'type' => 'url', 'default' => '/shop?sort=discount'],
                        'image' => ['label' => 'Banner image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'categories' => [
                    'title' => 'Shop by category',
                    'order' => 40,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Explore the catalogue'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Shop by category'],
                        'link_text' => ['label' => 'View all link', 'type' => 'text', 'default' => 'View all products →'],
                    ],
                ],
                'concerns' => [
                    'title' => 'Shop by health concern',
                    'order' => 50,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Browse with care'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Shop by health concern'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Categories are for browsing only—not a diagnosis or treatment recommendation.'],
                    ],
                ],
                'featured' => [
                    'title' => 'Featured products',
                    'order' => 60,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'A starting point'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Featured health essentials'],
                        'link_text' => ['label' => 'View all link', 'type' => 'text', 'default' => 'Shop the full range →'],
                    ],
                ],
                'promotions' => [
                    'title' => 'Promotion cards',
                    'order' => 70,
                    'optional' => true,
                    'fields' => [
                        'one_eyebrow' => ['label' => 'Card 1 eyebrow', 'type' => 'text', 'default' => 'Sample promotion'],
                        'one_title' => ['label' => 'Card 1 heading', 'type' => 'textarea', 'default' => 'Supplement offers\ngo here.'],
                        'one_copy' => ['label' => 'Card 1 description', 'type' => 'textarea', 'default' => 'Configure approved discounts and terms before publishing →'],
                        'one_url' => ['label' => 'Card 1 URL', 'type' => 'url', 'default' => '/shop?category=Vitamins%20%26%20Supplements'],
                        'two_eyebrow' => ['label' => 'Card 2 eyebrow', 'type' => 'text', 'default' => 'Explore a bundle'],
                        'two_title' => ['label' => 'Card 2 heading', 'type' => 'textarea', 'default' => 'Thoughtful picks,\ntogether.'],
                        'two_copy' => ['label' => 'Card 2 description', 'type' => 'textarea', 'default' => 'Bundle pricing is not enabled in this demo →'],
                        'two_url' => ['label' => 'Card 2 URL', 'type' => 'url', 'default' => '/shop'],
                        'three_eyebrow' => ['label' => 'Card 3 eyebrow', 'type' => 'text', 'default' => 'Prescription care'],
                        'three_title' => ['label' => 'Card 3 heading', 'type' => 'textarea', 'default' => 'Rx items are\nclearly labelled.'],
                        'three_copy' => ['label' => 'Card 3 description', 'type' => 'textarea', 'default' => 'Upload a prescription during checkout →'],
                        'three_url' => ['label' => 'Card 3 URL', 'type' => 'url', 'default' => '/shop?rx=rx'],
                    ],
                ],
                'trust' => [
                    'title' => 'Trust information',
                    'order' => 80,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Built with clarity'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'What this storefront is designed to do'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Operational trust claims must be verified before your site goes live.'],
                    ],
                ],
                'prescription' => [
                    'title' => 'Prescription information',
                    'order' => 90,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'For products marked Rx'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Prescription upload, in three steps.'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Add the requested product to your cart, upload a valid prescription at checkout, then wait for the required pharmacy review.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Browse Rx-labelled products'],
                        'button_url' => ['label' => 'Button URL', 'type' => 'url', 'default' => '/shop?rx=rx'],
                    ],
                ],
                'brands' => [
                    'title' => 'Featured brands',
                    'order' => 100,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Partner brands'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Featured brands'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Replace demo labels with approved brand assets.'],
                    ],
                ],
                'testimonials' => [
                    'title' => 'Farlex Pharma videos',
                    'order' => 110,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'From our YouTube channel'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Farlex Pharma videos'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Watch videos from Farlex Pharma on YouTube. Customer testimonials reflect individual experiences.'],
                    ],
                ],
                'articles' => [
                    'title' => 'Helpful reading',
                    'order' => 120,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Helpful reading'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Health & product guides'],
                        'link_text' => ['label' => 'View all link', 'type' => 'text', 'default' => 'More articles →'],
                    ],
                ],
                'newsletter' => [
                    'title' => 'Newsletter',
                    'order' => 140,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'A thoughtful inbox'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Notes for your wellness routine.'],
                        'copy' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Subscribe for product and store updates. No medical advice or treatment claims.'],
                    ],
                ],
            ],
        ],
        'shop' => [
            'title' => 'Shop & categories',
            'url' => '/shop',
            'group' => 'Storefront',
            'description' => 'Edit the catalogue introduction and no-results content. Product filters remain live.',
            'sections' => [
                'intro' => [
                    'title' => 'Shop page introduction',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Demo catalogue · India'],
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Shop health essentials'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'These are sample product records with placeholder prices and packaging. Replace all catalogue details with approved manufacturer and pharmacy information.'],
                    ],
                ],
                'filters' => [
                    'title' => 'Filter panel',
                    'order' => 20,
                    'fields' => [
                        'title' => ['label' => 'Filter heading', 'type' => 'text', 'default' => 'Filter products'],
                    ],
                ],
                'empty' => [
                    'title' => 'No results message',
                    'order' => 30,
                    'fields' => [
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'No matching products'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Try clearing one or more filters.'],
                    ],
                ],
            ],
        ],
        'product' => [
            'title' => 'Product details template',
            'url' => '/products/{product-slug}',
            'group' => 'Storefront',
            'description' => 'Edit shared product-page labels. Product names, media, prices, and medicine details stay in Products.',
            'sections' => [
                'product_info' => [
                    'title' => 'Product information',
                    'order' => 10,
                    'fields' => [
                        'tax_note' => ['label' => 'Price note', 'type' => 'textarea', 'default' => 'Demo price, not for sale · Confirm applicable tax treatment before launch'],
                        'demo_label' => ['label' => 'Demo badge', 'type' => 'text', 'default' => 'Demo listing'],
                        'stock_label' => ['label' => 'Stock label', 'type' => 'text', 'default' => 'Stock:'],
                        'add_to_cart' => ['label' => 'Add to cart button', 'type' => 'text', 'default' => 'Add to cart'],
                        'buy_now' => ['label' => 'Buy now button', 'type' => 'text', 'default' => 'Buy now'],
                        'wishlist' => ['label' => 'Wishlist button', 'type' => 'text', 'default' => '♡ Add to wishlist'],
                        'similar_title' => ['label' => 'Similar products heading', 'type' => 'text', 'default' => 'More in'],
                        'frequently_title' => ['label' => 'Related products heading', 'type' => 'text', 'default' => 'More demo products'],
                        'reviews_title' => ['label' => 'Reviews heading', 'type' => 'text', 'default' => 'No verified reviews yet'],
                        'reviews_copy' => ['label' => 'Reviews description', 'type' => 'textarea', 'default' => 'Reviews and ratings will appear after a verified review collection and moderation process is configured.'],
                        'medical_disclaimer' => ['label' => 'Medical disclaimer', 'type' => 'textarea', 'default' => 'Medicines should be taken only as directed by a registered medical practitioner. Product information shown here is a demo placeholder and must be replaced with approved label copy.'],
                    ],
                ],
            ],
        ],
        'cart' => [
            'title' => 'Cart',
            'url' => '/cart',
            'group' => 'Shopping',
            'description' => 'Edit cart headings and empty-state copy. Cart quantities and pricing remain dynamic.',
            'sections' => [
                'heading' => [
                    'title' => 'Cart heading',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Review your selection'],
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Your cart'],
                    ],
                ],
                'empty' => [
                    'title' => 'Empty cart',
                    'order' => 20,
                    'fields' => [
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Your cart is waiting for you.'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Browse the demo catalogue and add an item to continue.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Browse products'],
                        'button_url' => ['label' => 'Button URL', 'type' => 'url', 'default' => '/shop'],
                    ],
                ],
                'summary' => [
                    'title' => 'Price summary',
                    'order' => 30,
                    'fields' => [
                        'title' => ['label' => 'Summary heading', 'type' => 'text', 'default' => 'Price details'],
                        'checkout_button' => ['label' => 'Checkout button', 'type' => 'text', 'default' => 'Continue to checkout'],
                    ],
                ],
            ],
        ],
        'wishlist' => [
            'title' => 'Wishlist',
            'url' => '/wishlist',
            'group' => 'Shopping',
            'description' => 'Edit wishlist headings and empty-state copy. Saved products remain session-based.',
            'sections' => [
                'heading' => [
                    'title' => 'Wishlist heading',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Saved in this browser session'],
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Your wishlist'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Wishlist items are stored temporarily in this browser session; sign-in and cross-device sync are not configured.'],
                    ],
                ],
                'empty' => [
                    'title' => 'Empty wishlist',
                    'order' => 20,
                    'fields' => [
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'No saved products yet.'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Use the heart on a product card to save a demo item.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Browse catalogue'],
                    ],
                ],
            ],
        ],
        'checkout' => [
            'title' => 'Checkout',
            'url' => '/checkout',
            'group' => 'Shopping',
            'description' => 'Edit checkout instructions and labels. Customer data, Rx validation, and order totals remain protected.',
            'sections' => [
                'heading' => [
                    'title' => 'Checkout introduction',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Secure checkout layout · demo only'],
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Delivery details'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'This prototype does not process payments or fulfil orders. Sample product data must be reviewed before launch.'],
                    ],
                ],
                'contact' => [
                    'title' => 'Contact details section',
                    'order' => 20,
                    'fields' => [
                        'title' => ['label' => 'Section heading', 'type' => 'text', 'default' => 'Contact information'],
                    ],
                ],
                'prescription' => [
                    'title' => 'Prescription upload instructions',
                    'order' => 30,
                    'fields' => [
                        'title' => ['label' => 'Section heading', 'type' => 'text', 'default' => 'Prescription required to continue'],
                        'description' => ['label' => 'Instructions', 'type' => 'textarea', 'default' => 'Your cart contains an Rx-labelled product. Upload a clear prescription (PDF, JPG or PNG, up to 8 MB). A qualified pharmacy professional must review it before fulfilment.'],
                    ],
                ],
                'payment' => [
                    'title' => 'Payment information',
                    'order' => 40,
                    'fields' => [
                        'title' => ['label' => 'Section heading', 'type' => 'text', 'default' => 'Payment method'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Options are for interface preview only. No payment is taken.'],
                        'submit' => ['label' => 'Place order button', 'type' => 'text', 'default' => 'Place demo order'],
                    ],
                ],
            ],
        ],
        'order' => [
            'title' => 'Order confirmation',
            'url' => '/orders/{order-number}',
            'group' => 'Shopping',
            'description' => 'Edit order confirmation labels. Order references, status, and totals remain dynamic.',
            'sections' => [
                'confirmation' => [
                    'title' => 'Order confirmation',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Demo order reference'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'This order is recorded as a demo only. It has not been paid, reviewed or dispatched.'],
                        'items_title' => ['label' => 'Items heading', 'type' => 'text', 'default' => 'Items'],
                        'continue_button' => ['label' => 'Continue button', 'type' => 'text', 'default' => 'Continue browsing'],
                    ],
                ],
            ],
        ],
        'login' => [
            'title' => 'Customer sign-in',
            'url' => '/login',
            'group' => 'Customer',
            'description' => 'Edit customer sign-in instructions. OTP delivery and authentication behavior remain unchanged.',
            'sections' => [
                'login' => [
                    'title' => 'Sign-in panel',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Your account'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Sign in with your mobile'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'OTP delivery is not enabled in this demo. No code will be sent.'],
                        'button_text' => ['label' => 'Button text', 'type' => 'text', 'default' => 'Request OTP'],
                    ],
                ],
            ],
        ],
        'account' => [
            'title' => 'Customer account',
            'url' => '/account',
            'group' => 'Customer',
            'description' => 'Edit account page headings and feature descriptions. Authentication remains unchanged.',
            'sections' => [
                'heading' => [
                    'title' => 'Account introduction',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Customer account'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Your health essentials, in one place.'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'Customer accounts are not active until OTP delivery and secure authentication are configured.'],
                    ],
                ],
                'features' => [
                    'title' => 'Account feature cards',
                    'order' => 20,
                    'fields' => [
                        'orders_title' => ['label' => 'Orders card heading', 'type' => 'text', 'default' => 'My orders'],
                        'orders_copy' => ['label' => 'Orders card description', 'type' => 'textarea', 'default' => 'Order history and tracking appear after verified account sign-in is implemented.'],
                        'prescriptions_title' => ['label' => 'Prescriptions card heading', 'type' => 'text', 'default' => 'Saved prescriptions'],
                        'prescriptions_copy' => ['label' => 'Prescriptions card description', 'type' => 'textarea', 'default' => 'Not enabled. Prescription files require reviewed access and retention controls.'],
                        'addresses_title' => ['label' => 'Addresses card heading', 'type' => 'text', 'default' => 'Addresses'],
                        'addresses_copy' => ['label' => 'Addresses card description', 'type' => 'textarea', 'default' => 'Saved addresses are not enabled in this demo.'],
                        'wishlist_title' => ['label' => 'Wishlist card heading', 'type' => 'text', 'default' => 'Wishlist'],
                        'wishlist_copy' => ['label' => 'Wishlist card description', 'type' => 'textarea', 'default' => 'Save products temporarily in this browser session.'],
                        'reminders_title' => ['label' => 'Reminders card heading', 'type' => 'text', 'default' => 'Refill reminders'],
                        'reminders_copy' => ['label' => 'Reminders card description', 'type' => 'textarea', 'default' => 'Reminder scheduling is not connected.'],
                        'profile_title' => ['label' => 'Profile card heading', 'type' => 'text', 'default' => 'Profile'],
                        'profile_copy' => ['label' => 'Profile card description', 'type' => 'textarea', 'default' => 'Profile details are not collected until account security is configured.'],
                    ],
                ],
            ],
        ],
        'articles' => [
            'title' => 'Health articles',
            'url' => '/health-articles',
            'group' => 'Content',
            'reorderable' => true,
            'description' => 'Edit guide copy and upload optional article-card images. Keep medical copy reviewed.',
            'sections' => [
                'heading' => [
                    'title' => 'Articles introduction',
                    'order' => 10,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'General information'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Health & product guides'],
                        'description' => ['label' => 'Description', 'type' => 'textarea', 'default' => 'These general reading notes do not diagnose, treat, or replace advice from a registered medical practitioner or pharmacist.'],
                    ],
                ],
                'article_one' => [
                    'title' => 'Guide 1: Product labels',
                    'order' => 20,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Product information'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'How to read a product label'],
                        'copy' => ['label' => 'Guide text', 'type' => 'textarea', 'default' => 'Check the product name, pack size, manufacturer details, batch information, expiry date, and storage instructions on the original packaging. Ask a pharmacist if any information is unclear.'],
                        'image' => ['label' => 'Guide image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'article_two' => [
                    'title' => 'Guide 2: Product storage',
                    'order' => 30,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Everyday care'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Keep products in original packaging'],
                        'copy' => ['label' => 'Guide text', 'type' => 'textarea', 'default' => 'Follow the storage information supplied with the specific product and keep items away from children. Do not use products past their printed expiry date or if packaging appears damaged.'],
                        'image' => ['label' => 'Guide image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'article_three' => [
                    'title' => 'Guide 3: Ask your pharmacist',
                    'order' => 40,
                    'optional' => true,
                    'fields' => [
                        'eyebrow' => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Pharmacy support'],
                        'title' => ['label' => 'Heading', 'type' => 'text', 'default' => 'Questions to ask your pharmacist'],
                        'copy' => ['label' => 'Guide text', 'type' => 'textarea', 'default' => 'Ask a qualified pharmacist about the instructions on your product label, possible interactions, and how to store a product. For diagnosis or treatment advice, consult a registered medical practitioner.'],
                        'image' => ['label' => 'Guide image', 'type' => 'image', 'default' => null],
                    ],
                ],
                'disclaimer' => [
                    'title' => 'Article disclaimer',
                    'order' => 50,
                    'fields' => [
                        'copy' => ['label' => 'Disclaimer', 'type' => 'textarea', 'default' => 'This demo content is general and not medical advice. Product-specific information must be reviewed by qualified professionals before publication.'],
                    ],
                ],
            ],
        ],
        'privacy' => [
            'title' => 'Privacy policy',
            'url' => '/policies/privacy',
            'group' => 'Policies',
            'description' => 'Edit privacy policy draft text. Obtain qualified legal and pharmacy compliance review before publishing.',
            'sections' => [
                'policy' => [
                    'title' => 'Privacy policy content',
                    'order' => 10,
                    'fields' => [
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Privacy & health data'],
                        'intro' => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'This is a review draft, not a published privacy policy.'],
                        'section_one' => ['label' => 'Section 1', 'type' => 'textarea', 'default' => 'Document what personal and health-related data the live service collects, why it is needed, who can access it, and how long it is retained.'],
                        'section_two' => ['label' => 'Section 2', 'type' => 'textarea', 'default' => 'Prescription uploads in this demo are kept on private local storage and are not exposed through a public URL. Production deployment still needs access controls, encryption, retention limits, and a reviewed privacy notice.'],
                        'section_three' => ['label' => 'Section 3', 'type' => 'textarea', 'default' => 'Publish a contact for privacy requests and obtain legal review for applicable Indian privacy and pharmacy requirements before launch.'],
                    ],
                ],
            ],
        ],
        'terms' => [
            'title' => 'Terms of service',
            'url' => '/policies/terms',
            'group' => 'Policies',
            'description' => 'Edit terms draft text. Obtain qualified legal and pharmacy compliance review before publishing.',
            'sections' => [
                'policy' => [
                    'title' => 'Terms content',
                    'order' => 10,
                    'fields' => [
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Terms of service'],
                        'intro' => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'Replace this draft with terms reviewed for your business and operating jurisdictions.'],
                        'section_one' => ['label' => 'Section 1', 'type' => 'textarea', 'default' => 'Product information, prices, discounts, availability, and delivery estimates in this demo are placeholders.'],
                        'section_two' => ['label' => 'Section 2', 'type' => 'textarea', 'default' => 'The demo does not accept payment or establish a real sale. Add final order, cancellation, prescription-review, and customer-support terms before launch.'],
                        'section_three' => ['label' => 'Section 3', 'type' => 'textarea', 'default' => 'Nothing on this site is a substitute for advice from a registered medical practitioner or pharmacist.'],
                    ],
                ],
            ],
        ],
        'shipping' => [
            'title' => 'Shipping policy',
            'url' => '/policies/shipping',
            'group' => 'Policies',
            'description' => 'Edit shipping policy draft text. Confirm serviceability and delivery terms before publishing.',
            'sections' => [
                'policy' => [
                    'title' => 'Shipping policy content',
                    'order' => 10,
                    'fields' => [
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Shipping policy'],
                        'intro' => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'Delivery pricing and timing are illustrative until a logistics service is configured.'],
                        'section_one' => ['label' => 'Section 1', 'type' => 'textarea', 'default' => 'The checkout uses sample free-delivery and delivery-fee thresholds. Confirm serviceable pincodes, delivery partners, and timelines before publishing.'],
                        'section_two' => ['label' => 'Section 2', 'type' => 'textarea', 'default' => 'Prescription items must be reviewed by a qualified pharmacy professional before any fulfilment decision.'],
                        'section_three' => ['label' => 'Section 3', 'type' => 'textarea', 'default' => '', 'required' => false],
                    ],
                ],
            ],
        ],
        'refund' => [
            'title' => 'Refund & returns policy',
            'url' => '/policies/refund',
            'group' => 'Policies',
            'description' => 'Edit refund policy draft text. Obtain qualified legal and pharmacy compliance review before publishing.',
            'sections' => [
                'policy' => [
                    'title' => 'Refund policy content',
                    'order' => 10,
                    'fields' => [
                        'title' => ['label' => 'Page heading', 'type' => 'text', 'default' => 'Refund & returns'],
                        'intro' => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'This draft requires review against your product types and applicable rules.'],
                        'section_one' => ['label' => 'Section 1', 'type' => 'textarea', 'default' => 'Do not publish blanket return or refund promises for medicines without confirming legal and operational requirements.'],
                        'section_two' => ['label' => 'Section 2', 'type' => 'textarea', 'default' => 'Add a real support process, refund timelines, cancellation rules, and payment-provider handling before enabling live orders.'],
                        'section_three' => ['label' => 'Section 3', 'type' => 'textarea', 'default' => '', 'required' => false],
                    ],
                ],
            ],
        ],
    ],
];
