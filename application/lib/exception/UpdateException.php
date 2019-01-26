<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/30
 * Time: 1:56 AM
 */

namespace app\lib\exception;


class UpdateException extends BaseException
{
    public $code = 401;
    public $msg = '修改操作失败';
    public $errorCode = 50001;

}