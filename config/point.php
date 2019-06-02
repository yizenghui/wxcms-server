<?php

return [

    /*
     * 积分系统是否启动?
     */
    'enabled' => env('POINT_ENABLED', true),

    /**
     * 周期性结算 true 时当前可用积分需要周期结算，false 是余额为可用积分
     */
    'cycle_clearing' => env('POINT_CYCLE_CLEARING', false),
    
    /*
     * 默认pid 如果用户pid为0，默认为此值。
     */
    'default_fromid' => env('POINT_DEFAULT_FROMID', 0),

    /*
     * 签到可以获利积分
     */
    'sign_action' => env('POINT_SIGN_ACTION', 10),

    /**
     * 一天可以获得1次签到签到积分
     */
    'day_sign_num' => env('POINT_DAY_SIGN_NUM', 1),
    
    /*
     * 签到可以获利积分
     */
    'reward_action' => env('POINT_REWARD_ACTION', 10),

    /**
     * 一天可以获得1次签到签到积分
     */
    'day_reward_num' => env('POINT_DAY_REWARD_NUM', 1),
    
    /*
     * 阅读可以获得积分
     */
    'read_action' => env('POINT_READ_ACTION', 1),

    /*
     * 一天可以获得10次阅读积分
     */
    'day_read_num' => env('POINT_DAY_READ_NUM', 10),

    

    /*
     * 点赞可以获得积分
     */
    'like_action' => env('POINT_LiKE_ACTION', 2),

    /*
     * 一天可以获得5次点赞积分
     */
    'day_like_num' => env('POINT_DAY_LiKE_NUM', 5),

    
    /*
     * 邀请新人访问可以获得积分
     */
    'interview_action' => env('POINT_INTERVIEW_ACTION', 10),

    /*
     * 一天可以获得100次邀请新人积分
     */
    'day_interview_num' => env('POINT_DAY_INTERVIEW_NUM', 100),

    
    /*
     * 受邀用户签到
     */
    'fansign_action' => env('POINT_FANSIGN_ACTION', 2),

    /*
     * 一天可以获得300次邀请新人签到积分
     */
    'day_fansign_num' => env('POINT_DAY_FANSIGN_NUM', 300),


    /*
     * 组队双倍积分功能是否开启
     */
    'team_double_enabled' => env('POINT_TEAM_DOUBLE_ENABLED', false),
];
