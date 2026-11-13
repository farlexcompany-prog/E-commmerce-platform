<?php

return [
    'navigation' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'],
        ['section' => 'sales', 'label' => 'Sales', 'icon' => 'sales', 'children' => ['orders', 'shipments', 'invoices', 'refunds', 'transactions', 'bookings']],
        [
            'label' => 'Catalog',
            'icon' => 'catalog',
            'children' => ['products', 'configurable-products', 'categories', 'attributes', 'attribute-families', 'reviews'],
        ],
        ['section' => 'customers', 'icon' => 'customers'],
        ['section' => 'pages', 'label' => 'Pages', 'icon' => 'cms', 'children' => ['all-pages', 'site', 'home', 'customer-reviews', 'shop', 'product', 'cart', 'wishlist', 'checkout', 'order', 'login', 'account', 'articles', 'privacy', 'terms', 'shipping', 'refund']],
        ['section' => 'offers', 'icon' => 'offers'],
    ],
    'sections' => [
        'products' => [
            'title' => 'Products',
            'group' => 'Catalog',
            'description' => 'Create and maintain product names, pricing, pack sizes, and medicine details.',
        ],
        'configurable-products' => [
            'title' => 'Configurable Products',
            'group' => 'Catalog',
            'description' => 'Manage product variations such as pack sizes and available options.',
        ],
        'categories' => [
            'title' => 'Categories',
            'group' => 'Catalog',
            'description' => 'Organize products into storefront categories and health concerns.',
        ],
        'attributes' => [
            'title' => 'Attributes',
            'group' => 'Catalog',
            'description' => 'Define product details such as dosage form, strength, and prescription status.',
        ],
        'attribute-families' => [
            'title' => 'Attribute Families',
            'group' => 'Catalog',
            'description' => 'Group product attributes into reusable product information layouts.',
        ],
        'reviews' => [
            'title' => 'Reviews',
            'group' => 'Catalog',
            'description' => 'Review and moderate customer feedback for products.',
        ],
        'customers' => [
            'title' => 'Customers',
            'group' => 'Customers',
            'description' => 'View customer profiles, saved addresses, and account activity.',
        ],
        'offers' => [
            'title' => 'Offers',
            'group' => 'Offers',
            'description' => 'Create and manage coupons, discounts, and promotional campaigns.',
        ],
        'marketing' => [
            'title' => 'Marketing',
            'group' => 'Marketing',
            'description' => 'Manage newsletter subscribers and customer communications.',
        ],
        'reporting' => [
            'title' => 'Reporting',
            'group' => 'Reporting',
            'description' => 'Review order, product, and sales performance reports.',
        ],
        'shipments' => [
            'title' => 'Shipments',
            'group' => 'Sales',
            'description' => 'Shipment tracking and fulfilment tools are not configured in this demo.',
        ],
        'invoices' => [
            'title' => 'Invoices',
            'group' => 'Sales',
            'description' => 'Invoice generation and tax documents are not configured in this demo.',
        ],
        'refunds' => [
            'title' => 'Refunds',
            'group' => 'Sales',
            'description' => 'Refund processing is not connected in this demo.',
        ],
        'transactions' => [
            'title' => 'Transactions',
            'group' => 'Sales',
            'description' => 'Payment transactions are not connected in this demo.',
        ],
        'bookings' => [
            'title' => 'Bookings',
            'group' => 'Sales',
            'description' => 'Bookings are not configured in this demo.',
        ],
    ],
];
