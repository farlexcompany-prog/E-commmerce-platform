<?php

return [
    'navigation' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'],
        ['section' => 'sales', 'icon' => 'sales'],
        [
            'label' => 'Catalog',
            'icon' => 'catalog',
            'children' => ['products', 'configurable-products', 'categories', 'attributes', 'attribute-families', 'reviews'],
        ],
        ['section' => 'customers', 'icon' => 'customers'],
        ['section' => 'cms', 'icon' => 'cms'],
        ['section' => 'offers', 'icon' => 'offers'],
        ['section' => 'marketing', 'icon' => 'marketing'],
        ['section' => 'reporting', 'icon' => 'reporting'],
        ['section' => 'settings', 'icon' => 'settings'],
        ['section' => 'configure', 'icon' => 'configure'],
    ],
    'sections' => [
        'sales' => [
            'title' => 'Sales',
            'group' => 'Sales',
            'description' => 'Sales activity, orders, and transaction summaries will be organized here.',
        ],
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
        'cms' => [
            'title' => 'CMS',
            'group' => 'CMS',
            'description' => 'Manage storefront pages, banners, and health articles.',
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
        'settings' => [
            'title' => 'Settings',
            'group' => 'Settings',
            'description' => 'Configure store details, delivery rules, and business preferences.',
        ],
        'configure' => [
            'title' => 'Configure',
            'group' => 'Configure',
            'description' => 'Manage integrations and advanced storefront configuration.',
        ],
    ],
];
