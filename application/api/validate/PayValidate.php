<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 11:00
 */

namespace app\api\validate;


class PayValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'order_type' => 'require|isPositiveInteger|in:1,2',
    ];

    protected $scene = [
        'pre_card' => ['id', 'order_type'],
    ];

}