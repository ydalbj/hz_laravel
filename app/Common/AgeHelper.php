<?php

namespace App\Common;

use InvalidArgumentException;

class AgeHelper
{
    // 1岁2个月转换为14
    public static function string2MonthInt(string $age)
    {
        if ($age === '-1' || $age === '无限制') {
            return -1;
        }
        
        $age = str_replace(' ', '', $age);
        // preg_match_all('/(\d+).*岁.*(\d)*/u', $age, $matchs);
        preg_match_all('/\d+/', $age, $matchs);

        if (!$matchs[0]) {
            throw new InvalidArgumentException('输入年龄错误，请输入x岁x个月');
        }

        $month = intval($matchs[0][0]) * 12;

        if (isset($matchs[0][1])) {
            $month += intval($matchs[0][1]);
        }

        return $month;
    }

    // 14转换为1岁2个月
    public static function monthInt2String(int $age)
    {
        $content = '';
        if ($age < 0) {
            $content = '无限制';
        } else {
            $year = intval($age / 12);
            $month = $age % 12;

            $content = "$year 岁 ";
            if ($month > 0) {
                $content .= "$month 个月";
            }
        }

        return $content;
    }
}