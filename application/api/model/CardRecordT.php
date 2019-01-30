<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-29
 * Time: 01:15
 */

namespace app\api\model;


use think\Model;

class CardRecordT extends Model
{
    public static function getBalance($u_id)
    {
        $balance = self::where('u_id', $u_id)
            ->sum('count');
        return $balance;

    }

}