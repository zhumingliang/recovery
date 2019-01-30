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
        'c_id' => 'require|isPositiveInteger',
        'type' => 'require|in:1,2,3',
        'pay_type' => 'require|in:1,2',
        'min' => 'require',
        'max' => 'require',
        'price' => 'require|isPositiveInteger',
        'money' => 'require|isPositiveInteger',
    ];


    protected $scene = [
        'save' => ['type', 'price', 'min', 'max'],
        'order' => ['c_id','pay_type','money'],
        'update' => ['id', 'type', 'price', 'min', 'max'],
        'id' => ['id'],
    ];

}