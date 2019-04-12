<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 09:52
 */

namespace app\api\model;


use think\Model;

class UserCardT extends Model
{
    public function card()
    {
        return $this->belongsTo('CardT',
            'c_id', 'id');
    }

    public static function getInfo($order_num)
    {
        $info = self::where('order_number', $order_num)->with('card')->find();
        return $info;

    }


}