<?php
/**
 * 注意，这里的参数会被用户设置参数覆盖。  .evn命名与apps表字段相同(注意大小差异)
 */
return [

    // 当前小程序版本
    'mini_program_version'=> '1.0.0',

    // 当前小程序版本描述
    'mini_program_version_desc'=> '初始版本',
    
    /*
     * 积分系统是否启动?
     */
    'enabled' => env('POINT_ENABLED', true),

    /**
     * 积分类型(小程序展示积分标题)
     */
    'score_type' => env('POINT_SCORE_TYPE', '积分'),

     /**
      * 积分比值(小程序展示积分实值比)
      */
    'score_ratio' => env('POINT_SCORE_RATIO', 1), // 1 1:1  100 100:1 

    /*
     * 默认pid 如果用户pid为0，默认为此值。
     */
    'default_fromid' => env('POINT_DEFAULT_FROMID', 0),
    
    /*
     * 重复阅读同一篇文章给予奖励
     */
    'rereading_reward' => env('POINT_REREADING_REWARD', 1),
     
    /*
     * 重复激励同一篇文章给予奖励
     */
    'repeated_incentives' => env('POINT_REPEATED_INCENTIVES', 1),
     

    /**
     * 作者文章被激励(加分给作者不设上限)
     */
    'author_article_reward_action' => env('POINT_AUTHOR_ARTICLE_REWARD_ACTION', 5),


    /**
     * 周期性结算 true 时当前可用积分需要周期结算，false 是余额为可用积分
     */
    'cycle_clearing' => env('POINT_CYCLE_CLEARING', false),
    

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

    /**
     * 渠道奖励功能开启状态
     */
    'channel_status' => env('POINT_CHANNEL_STATUS', 1),

    /**
     * 展示任务版块
     */
    'show_task' => env('POINT_SHOW_TASK', 1),

    /**
     * 分享(老用户)访问奖励积分
     */
    'share_action' => env('POINT_SHARE_ACTION', 1),

    /**
     * 分享(老用户)访问奖励次数(一人一天一次)
     */
    'day_share_num' => env('POINT_DAY_SHARE_NUM', 100),

    /*
     * 点赞可以获得积分
     */
    'like_action' => env('POINT_LiKE_ACTION', 2),

    /*
     * 一天可以获得5次点赞积分
     */
    'day_like_num' => env('POINT_DAY_LiKE_NUM', 5),

    
    /*
     * 激励文章可以获得积分
     */
    'reward_article_action' => env('POINT_REWARD_ARTICLE_ACTION', 5),

    /*
     * 一天最多可以获得5次激励文章积分
     */
    'day_reward_article_num' => env('POINT_DAY_REWARD_ARTICLE_NUM', 5),

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
     * 一天可以获得300次受邀用户签到积分
     */
    'day_fansign_num' => env('POINT_DAY_FANSIGN_NUM', 300),

    /*
     * 受邀用户激励
     */
    'fanreward_action' => env('POINT_FANREWARD_ACTION', 2),

    /*
     * 一天可以获得1000次受邀用户激励积分
     */
    'day_fanreward_num' => env('POINT_DAY_FANREWARD_NUM', 1000),


    /*
     * 组队双倍积分功能是否开启
     */
    'team_double_enabled' => env('POINT_TEAM_DOUBLE_ENABLED', false),
];
