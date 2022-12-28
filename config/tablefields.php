<?php

return [
    'users'    => [
        'jagel_user_id' => [
            'label' => 'Jagel User ID',
            'type'  => 'text',
        ],
        'name' => [
            'label' => 'Nama',
            'type'  => 'text',
        ],
        'username' => [
            'label' => 'Username',
            'type'  => 'text',
        ],
        'password' => [
            'label' => 'Password',
            'type'  => 'password',
        ],
        'phone' => [
            'label' => 'Phone',
            'type'  => 'tel',
        ],
        'email' => [
            'label' => 'Email',
            'type'  => 'email',
        ],
        'status_partner' => [
            'label' => 'Status Partner',
            'type'  => 'options:0|1',
        ],
        'status_driver' => [
            'label' => 'Status Driver',
            'type'  => 'options:0|1',
        ],
        'photo' => [
            'label' => 'Photo',
            'type'  => 'file',
        ]
    ],
    'balance' => [
        'user_id' => [
            'label' => 'User',
            'type'  => 'options-obj:users,id,name',
        ],
        'amount' => [
            'label' => 'Amount',
            'type'  => 'number',
        ],
    ],
    'topup' => [
        'userid',
        'pesanan_nomor',
        'pesanan_total',
        'hp',
        'payment_type',
        'payment_ref',
        'status',
        'created_at',
        'payment_at',
    ]
];