<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-29
 * Time: 10:59
 */

namespace app\api\validate;


class OrderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'pay_type' => 'require|in:1,2,3',
        'count' => 'require|isPositiveInteger',
        'money' => 'require|isPositiveInteger',
    ];


    protected $scene = [
        'save' => ['pay_type','count', 'money']
    ];


}