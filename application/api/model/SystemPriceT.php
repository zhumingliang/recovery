<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-25
 * Time: 10:45
 */

namespace app\api\model;


use think\Model;

class SystemPriceT extends Model
{

    public function getPriceAttr($value, $data)
    {
        return $value / 100;
    }
}