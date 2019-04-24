<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-24
 * Time: 10:46
 */

namespace app\api\model;


use think\Model;

class CardT extends Model
{
    public function getPriceAttr($value, $data)
    {
        return $value / 100;
    }

    public function getMaxAttr($value, $data)
    {
        return $value == 0 ? '无限' : $value;
    }

}