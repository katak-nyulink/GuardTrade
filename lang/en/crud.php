<?php

return [
    'users' => [
        'title' => 'Users',
        'description' => 'List of users',
        'inputs' => [
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Name',
            ],
            'email' => [
                'label' => 'Email',
                'placeholder' => 'Email',
            ],
            'avatar' => [
                'label' => 'Avatar',
                'placeholder' => 'Avatar',
            ],
            'is_active' => [
                'label' => 'Active',
                'placeholder' => 'Active',
            ],
            'is_admin' => [
                'label' => 'Admin',
                'placeholder' => 'Admin',
            ],
            'role' => [
                'label' => 'Role',
                'placeholder' => 'Role'
            ],
            'created_at' => [
                'label' => 'Created at',
                'placeholder' => 'Created at',
            ],
            'modified_at' => [
                'label' => 'Updated at',
                'placeholder' => 'Updated at',
            ],
            'deleted_at' => [
                'label' => 'Deleted at',
                'placeholder' => 'Deleted at',
            ],
        ],

    ],
    'categories' => [
        'title' => 'Categories',
        'description' => 'List of categories',
        'inputs' => [
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Name',
            ],
            'slug' => [
                'label' => 'Slug',
                'placeholder' => 'Slug',
            ],
            'description' => [
                'label' => 'Description',
                'placeholder' => 'Description',
            ],
            'parent_id' => [
                'label' => 'Parent',
                'placeholder' => 'Parent',
            ],
            'parent' => [
                'label' => 'Parent',
                'placeholder' => 'Parent',
            ],
            'created_at' => [
                'label' => 'Created at',
                'placeholder' => 'Created at',
            ],
            'modified_at' => [
                'label' => 'Updated at',
                'placeholder' => 'Updated at',
            ],
            'deleted_at' => [
                'label' => 'Deleted at',
                'placeholder' => 'Deleted at',
            ],
        ],
    ],
];
