<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 15:57
 */

namespace app\api\model;


use think\Model;

class OrderT extends Model
{

    public static function getInfo($order_num)
    {
        $info = self::where('order_number', $order_num)->find();
        return $info;

    }

}