<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-23
 * Time: 23:13
 */

namespace app\api\model;


use think\Model;

class UserT extends Model
{
    public static function updateUserWithPhone($id, $phone)
    {
        return self::update(['phone' => $phone], ['id' => $id]);
    }

}