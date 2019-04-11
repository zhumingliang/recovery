<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 10:42
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\PayService;
use app\api\validate\PayValidate;

class Pay extends BaseController
{
    /**
     * @api {GET} /api/v1/pay/getPreOrder  11-Android端获取支付数据
     * @apiGroup  Android
     * @apiVersion 1.0.1
     * @apiDescription Android端获取支付数据（购买会员卡；直接支付恢复订单）
     * @apiExample {get}  请求样例:
     * http://recovery.mengant.cn/api/v1/pay/getPreOrder?id=1&order_type=1
     * @apiParam (请求参数说明) {int} id 订单id
     * @apiParam (请求参数说明) {int} order_type 订单类别：1 | 购买会员卡支付；2 | 直接支付恢复数据订单
     * @apiSuccessExample {json} 支付宝返回样例:
     * {
     * {"pay_params":"alipay_sdk=alipay-sdk-php-20180705&app_id=2019011162869266&biz_content=%7B%22body%22%3A%22%5Cu652f%5Cu4ed8%5Cu4f1a%5Cu5458%5Cu5361%22%2C%22subject%22%3A%22%5Cu652f%5Cu4ed8%5Cu4f1a%5Cu5458%5Cu5361%22%2C%22out_trade_no%22%3A%22C128875414239899%22%2C%22timeout_express%22%3A%2290m%22%2C%22total_amount%22%3A10%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22passback_params%22%3A%221%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&notify_url=recovery.mengant.cn%2Fapi%2Fv1%2Fpay%2Falipay%2Fnotify&sign_type=RSA2&timestamp=2019-01-28+23%3A02%3A59&version=1.0&sign=IBfzVcIRWU65JwcAKfh4oUh4fGybP3rpFStdmCjj7w9Jqc2jNy5HefWtyp5lgWFwuU4bFwIpZn0hoYjPhe2uACZNmJSIU1xMAHnHs%2BTrAi%2Bp84ihTnlrvMhOVhVXfRFb0VQw7BgHM9QSl1Bo%2FUVow3wQMbkHohg7wvc3sOkxJtbS1%2BsQa%2FkL2rQp3XdnylOBu2w6Hz5OFTSm7hcpVaQGbzPvQ7Nt13LGkta%2FTIs5GTPEqfz34k32vybYSF7wkJYFW3Q2%2B%2BBgw%2B%2FjozG%2Bch9zQRjPI8BbRavplK1l8jPh8PSokdrnzHOj9G0InZanJzz3YWIr46tZucysyBJSGym1%2Bg%3D%3D"}     * }
     * @apiSuccess (返回参数说明) {String} pay_params 安卓支付所需数据
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getPreOrder()
    {
        (new PayValidate())->scene('pre_card')->goCheck();
        $params = $this->request->param();
        $pay = new PayService($params['order_type'], $params['id']);
        return json([
            'pay_params' => $pay->getPreOrder()
        ]);
    }

    /**
     * 接口支付宝异步通知
     */
    public function aliNotify()
    {
        (new PayService('', ''))->aliNotify();
    }

    public function wxNotify()
    {

    }


}