<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 10:18
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\OrderService;
use app\api\validate\OrderValidate;

class Order extends BaseController
{
    /**
     * @api {POST} /api/v1/order/save  12-Android端新增恢复数据订单
     * @apiGroup  Android
     * @apiVersion 1.0.1
     * @apiDescription Android端新增恢复数据订单
     * @apiExample {post}  请求样例:
     *    {
     *       "pay_type": 1,
     *       "count": 1000,
     *       "money": 20,
     *       "phone": 18956225230
     *     }
     * @apiParam (请求参数说明) {int} pay_type  支付类别：1 |微信；2 | 支付宝；3 | 会员卡
     * @apiParam (请求参数说明) {int} count  恢复数量
     * @apiParam (请求参数说明) {int} money  支付金额
     * @apiParam (请求参数说明) {String} phone  用户手机号
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0,"data":{"id":1}}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @apiSuccess (返回参数说明) {String} data 返回信息
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\SaveException
     */
    public function save()
    {
        (new OrderValidate())->scene('save')->goCheck();
        $params = $this->request->param();
        $res = (new OrderService())->save($params);
        return json($res);

    }

}