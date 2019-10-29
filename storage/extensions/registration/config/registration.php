<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 新用户注册成功之后的默认角色
    |--------------------------------------------------------------------------
    |
    | 建议先新增一个普通用户角色，然后设置下面的配置。
    |
    */
    'default_role' => 'administrator',

    /*
    |--------------------------------------------------------------------------
    | 视图文件
    |--------------------------------------------------------------------------
    |
    | 你可以修改这里用来自定义下面几个页面的样式。
    |
    */
    'views' => [

        // 登录页
        'login'         => 'registration::login',

        // 注册页
        'register'      => 'registration::register',

        // 注册邮件发送成功页
        'verify'        => 'registration::verify',

        // 通知邮件视图
        'notifications' => [

            // 注册验证邮箱
            'verify_email'   => 'registration::notifications.verify-email',

            // 重置密码链接邮件
            'reset_password' => 'registration::notifications.reset-password',
        ],

        // 修改密码
        'passwords'     => [

            // 忘记密码，填写邮箱页面
            'email' => 'registration::passwords.email',

            // 重置密码form表单页
            'reset' => 'registration::passwords.reset',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 邮箱验证链接失效时间
    |--------------------------------------------------------------------------
    |
    */
    'verification_expire' => 60,
];