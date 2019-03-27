<?php

return [

    /*
     * 积分系统是否启动?
     */
    'enabled' => env('POINT_ENABLED', true),

    /*
     * 签到可以获利积分
     */
    'sign_action' => env('POINT_SIGN_ACTION', 30),

    /**
     * 一天可以获得1次签到签到积分
     */
    'day_sign_num' => env('POINT_DAY_SIGN_NUM', 1),
    
    /*
     * 阅读可以获得积分
     */
    'read_action' => env('POINT_READ_ACTION', 2),

    /*
     * 一天可以获得10次阅读积分
     */
    'day_read_num' => env('POINT_DAY_READ_NUM', 10),

    
    /*
     * 点赞可以获得积分
     */
    'like_action' => env('POINT_LiKE_ACTION', 5),

    /*
     * 一天可以获得6次点赞积分
     */
    'day_like_num' => env('POINT_DAY_LiKE_NUM', 6),

    
    /*
     * 组队双倍积分功能是否开启
     */
    'team_double_enabled' => env('POINT_TEAM_DOUBLE_ENABLED', false),
];
