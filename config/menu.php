<?php

return [
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'icon' => 'heroicon-o-home',
            'route' => 'dashboard',
        ],
        [
            'title' => 'Apps',
            'type' => 'header',
        ],
        [
            'title' => 'Email',
            'icon' => 'heroicon-o-envelope',
            'submenu' => [
                [
                    'title' => 'Inbox',
                    'route' => 'dashboard',
                    'badge' => '50',
                    'badge_end' => true,
                ],
                [
                    'title' => 'View Message',
                    'route' => 'dashboard.view-message',
                ],
                [
                    'title' => 'Compose',
                    'route' => 'dashboard.compose',
                ]
            ],
            'routes' => ['dashboard', 'dashboard.view-message', 'dashboard.compose']
        ],
        [
            'title' => 'Pages',
            'type' => 'header',
        ],
        [
            'title' => 'Generic',
            'icon' => 'heroicon-o-queue-list',
            'submenu' => [
                [
                    'title' => 'Empty Page',
                    'route' => 'generic.empty',
                ],
            ],
            'routes' => ['generic.empty']
        ],
        [
            'title' => 'Authentication',
            'icon' => 'heroicon-o-key',
            'submenu' => [
                [
                    'title' => 'Basic',
                    'type' => 'header',
                ],
                [
                    'title' => 'Sign In',
                    'route' => 'auth.signin',
                ],
                [
                    'title' => 'Sign Up',
                    'route' => 'auth.signup',
                ],
                [
                    'title' => 'Forgot Password',
                    'route' => 'auth.forgot',
                ],
                [
                    'title' => 'Reset Password',
                    'route' => 'auth.reset',
                ],
                [
                    'title' => 'Email Verification',
                    'route' => 'auth.verify',
                ],
            ],
            'routes' => ['auth.signin', 'auth.signup', 'auth.forgot', 'auth.reset', 'auth.verify']
        ],
    ],

    'menuItems' => [
        [
            'label' => 'Tables',
            'icon' => 'heroicon-o-table-cells',
            'url' => '#'
        ],
        [
            'label' => 'Forms',
            'icon' => 'heroicon-o-clipboard-document-list',
            'url' => '#'
        ],
        [
            'label' => 'Charts',
            'icon' => 'heroicon-o-chart-bar',
            'url' => '#'
        ],
        [
            'label' => 'Maps',
            'icon' => 'heroicon-o-map',
            'url' => '#'
        ],
        [
            'label' => 'Pages',
            'icon' => 'heroicon-o-document-text',
            'subitems' => [
                [
                    'label' => 'Generic',
                    'url' => '#'
                ],
                [
                    'label' => 'Authentication',
                    'url' => '#'
                ],
                [
                    'label' => 'Profile',
                    'url' => '#'
                ],
                [
                    'label' => 'Invoices',
                    'url' => '#'
                ],
                [
                    'label' => 'Errors',
                    'icon' => 'heroicon-o-exclamation-triangle',
                    'subitems' => [
                        [
                            'label' => '404',
                            'subitems' => [
                                ['label' => 'Basic', 'url' => '#'],
                                ['label' => 'Illustration', 'url' => '#'],
                                ['label' => 'Illustration Cover', 'url' => '#']
                            ]
                        ],
                        [
                            'label' => '500',
                            'subitems' => [
                                ['label' => 'Basic', 'url' => '#'],
                                ['label' => 'Illustration', 'url' => '#'],
                                ['label' => 'Illustration Cover', 'url' => '#']
                            ]
                        ],
                        [
                            'label' => 'Maintenance',
                            'subitems' => [
                                ['label' => 'FAQ', 'url' => '#']
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
