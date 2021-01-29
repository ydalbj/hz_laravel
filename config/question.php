<?php

return [
    'type' => [
        0 => '单选题',
        1 => '多选题',
        2 => '填空题',
        3 => '数字填空题',
    ],
    'sex' => [
        0 => '女孩',
        1 => '男孩',
    ],
    'max_age' => 120, // 单位`月`,10岁
    'default_subject_id' => 1,
    'default_result' => [
        '1' => [
            'age_standard' => 0,
            'age_formatted' => '0岁',
            'score' => 0,
            'group_name' => '大运动',
        ],
        '3' => [
            'age_standard' => 0,
            'age_formatted' => '0岁',
            'score' => 0,
            'group_name' => '个人社交',
        ],
        '5' => [
            'age_standard' => 0,
            'age_formatted' => '0岁',
            'score' => 0,
            'group_name' => '理解认知',
        ],
        '6' => [
            'age_standard' => 0,
            'age_formatted' => '0岁',
            'score' => 0,
            'group_name' => '语言发育',
        ],
        '7' => [
            'age_standard' => 0,
            'age_formatted' => '0岁',
            'score' => 0,
            'group_name' => '精细运动',
        ],
    ]
];