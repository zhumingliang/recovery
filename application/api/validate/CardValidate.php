<?php
/**
 * Created by PhpStorm.
 * User: zhumingliang
 * Date: 2018/4/24
 * Time: 下午10:59
 */

namespace app\api\validate;


class CardValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'type' => 'require|in:1,2,3',
        'min' => 'require',
        'max' => 'require',
        'price' => 'require|isPositiveInteger',
    ];


    protected $scene = [
        'save' => ['type', 'price', 'min', 'max'],
        'update' => ['id', 'type', 'price', 'min', 'max'],
        'id' => ['id'],
    ];

}