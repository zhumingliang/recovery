<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/30
 * Time: 1:56 AM
 */

namespace app\lib\exception;


class DeleteException extends BaseException
{
    public $code = 401;
    public $msg = '删除操作失败';
    public $errorCode = 60001;

}