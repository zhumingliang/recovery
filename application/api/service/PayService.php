<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 11:02
 */

namespace app\api\service;


use app\api\model\AlipayT;
use app\api\model\CardOrderT;
use app\api\model\CardT;
use app\api\model\LogT;
use app\api\model\OrderT;
use app\api\model\UserCardT;
use app\lib\enum\CommonEnum;
use app\lib\exception\PayException;

require __DIR__ . '/../../../extend/alipay/aop/AopClient.php';
require __DIR__ . '/../../../extend/alipay/aop/request/AlipayTradeAppPayRequest.php';

class PayService
{
    private $orderType = 0;
    private $payType = 0;
    private $orderId = 0;
    private $orderNumber = 0;
    private $payBody = '';

    public function __construct($order_type, $order_id)
    {
        $this->orderType = $order_type;
        $this->orderId = $order_id;

    }

    public function getPreOrder()
    {
        $orderMoney = $this->checkOrderValid();
        $info = $this->makePreOrder($orderMoney);
        return $info;


    }

    private
    function makePreOrder($totalPrice)
    {
        $info = '';
        if ($this->payType == CommonEnum::PAY_WX) {


        } elseif ($this->payType == CommonEnum::PAY_ALI) {
            $info = $this->makePreOrderForAli($totalPrice);
        }
        return $info;

    }

    private function makePreOrderForAli($totalPrice)
    {

        $aop = new \AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = config('alipay.appId');
        $aop->rsaPrivateKey = config('alipay.rsaPrivateKey');
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = config('alipay.alipayrsaPublicKey');
        $request = new \AlipayTradeAppPayRequest();

        $data = [
            'subject' => $this->orderType == CommonEnum::ORDER_RECOVERY ? '数据恢复-订单支付' : '购买会员卡-订单支付',
            'body' => $this->orderType == CommonEnum::ORDER_RECOVERY ? '数据恢复数量:' . $this->payBody : '购买会员类别：' . $this->payBody,
            'out_trade_no' => $this->orderNumber,
            'timeout_express' => '90m',
            'total_amount' => $totalPrice / 100,
            'product_code' => 'QUICK_MSECURITY_PAY',
            'passback_params' => urlencode($this->orderType),

        ];
        $bizcontent = json_encode($data);
        $request->setNotifyUrl(config('alipay.notify'));
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        return $response = $aop->sdkExecute($request);
        //return htmlspecialchars($response);
    }


    /**
     * 检测订单是否合法
     * @return array
     * @throws PayException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\Exception\DbException
     */
    private
    function checkOrderValid()
    {
        $order = self::getOrder();
        if (!$order) {
            throw new PayException(
                [
                    'msg' => '订单不存在',
                    'errorCode' => 150002
                ]
            );
        }
        if ($order->u_id != Token::getCurrentUid()) {
            // if ($order->u_id != 9) {
            throw new PayException(
                [
                    'msg' => '订单与用户不匹配',
                    'errorCode' => 150003
                ]);
        }

        if ($order->pay_id != CommonEnum::ORDER_STATE_INIT) {
            throw new PayException(
                [
                    'msg' => '订单已支付过啦',
                    'errorCode' => 150005,
                    'code' => 401
                ]);
        }
        $this->orderNumber = $order->order_number;
        $this->payType = $order->pay_type;
        return $order->money;
    }

    /**
     * @return OrderT|UserCardT|null
     * @throws \think\Exception\DbException
     */
    private
    function getOrder()
    {
        $order = array();
        if ($this->orderType == CommonEnum::ORDER_CARD) {
            $order = UserCardT::get($this->orderId);

            $card_info = CardT::where('id', $order->c_id)->find();
            $this->payBody = $card_info->name;
        } elseif ($this->orderType == CommonEnum::ORDER_RECOVERY) {
            $order = OrderT::get($this->orderId);
            $this->payBody = $order->count;
        }

        return $order;


    }


    public function aliNotify()
    {
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = config('alipay.alipayrsaPublicKey');

        //$post = json_decode('{"gmt_create":"2019-04-11 23:32:27","charset":"UTF-8","seller_email":"18956225230","subject":"\u8d2d\u4e70\u4f1a\u5458\u5361-\u8ba2\u5355\u652f\u4ed8","sign":"RalpF9YzvVNtTT9bHisju3YzkEazPNyvlR6k8wd\/AryB+l5pVM8T1UbVeC2Tk6Qd23zsrmwBLpp1xUatmV1x38pY+969nI1OZnufeKm9\/hKAoGBeCuU0rh2w57zeDbFpIZhwMi8DlkGfT8m6Mx\/nUkYmslWaq77eCqdrNBYABRLN4ypMcZst24GpMv5DvPDyLml4r+I223MCx23L6E6XeR37JX39742FFHCNprzHhfiGx4BRlNadxaWTbTt+uNrmGZlCa+GfGcIpypGPlbeqZZK+LY7+gyjHHWIX4+S70KiOrvTjZGyKIBQ6v18\/fnjWKEZ4+aYIBtOKLdgONW55YA==","body":"\u8d2d\u4e70\u4f1a\u5458\u7c7b\u522b\uff1a\u91d1\u94dc\u4f1a\u5458","buyer_id":"2088212481754967","invoice_amount":"0.20","notify_id":"2019041100222233228054961009626559","fund_bill_list":"[{\"amount\":\"0.20\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"0.20","app_id":"2019011162869266","buyer_pay_amount":"0.20","sign_type":"RSA2","seller_id":"2088312153206526","gmt_payment":"2019-04-11 23:32:28","notify_time":"2019-04-12 02:59:13","passback_params":"1","version":"1.0","out_trade_no":"C411967301465997","total_amount":"0.20","trade_no":"2019041122001454961028568034","auth_app_id":"2019011162869266","buyer_logon_id":"188****4025","point_amount":"0.00"}', true);
        $post = $_POST;//$flag = $aop->rsaCheckV1($post, NULL, "RSA2");  //验证签名
        //$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");  //验证签名
        if (1) {
            //校验通知数据的正确性
            $out_trade_no = $post['out_trade_no'];    //商户订单号
            $trade_no = $post['trade_no'];    //支付宝交易号
            $trade_status = $post['trade_status'];    //交易状态trade_status
            $total_amount = $post['total_amount'];    //订单的实际金额
            $notify_id = $post['notify_id'];         //通知校验ID。
            $notify_time = $post['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            $buyer_logon_id = $post['buyer_logon_id'];       //买家支付宝帐号；
            $passback_params = urldecode($post['passback_params']);
            $app_id = $post['app_id'];
            if ($app_id != config('alipay.appId')) {
                LogT::create(['msg' => 'app_id与商户本身不符合']);
                exit('fail');    //验证app_id是否为该商户本身
            }
            if ($trade_status != 'TRADE_FINISHED' && $trade_status != 'TRADE_SUCCESS') {
                LogT::create(['msg' => '买家付款失败']);
                exit('fail');
            }
            //校验订单的正确性
            if (!empty($out_trade_no)) {
                //1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
                //2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
                //3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）。
                //上述1、2、3有任何一个验证不通过，则表明本次通知是异常通知，务必忽略。在上述验证通过后商户必须根据支付宝不同类型的业务通知，正确的进行不同的业务处理，并且过滤重复的通知结果数据。
                //校验成功后在response中返回success，校验失败返回failure
                $parameter = array(
                    "out_trade_no" => $out_trade_no, //商户订单编号；
                    "trade_no" => $trade_no,     //支付宝交易号；
                    "total_amount" => $total_amount * 100,    //交易金额；
                    "trade_status" => $trade_status, //交易状态
                    "notify_id" => $notify_id,    //通知校验ID。
                    "notify_time" => $notify_time,  //通知的发送时间。
                    "buyer_logon_id" => $buyer_logon_id,  //买家支付宝帐号；
                    "passback_params" => $passback_params
                );
                $res = AlipayT::create($parameter);
                if (!$res) {
                    LogT::create(['msg' => '保存支付宝通知信息失败']);
                }

                if ($passback_params == CommonEnum::ORDER_CARD) {
                    $this->orderHandelForCard($res->id, $parameter['out_trade_no']);
                } elseif ($passback_params == CommonEnum::ORDER_RECOVERY) {
                    $this->orderHandelForRecovery($res->id, $parameter['out_trade_no']);

                } else {
                    LogT::create(['msg' => '返回参数异常']);
                }


            }
            echo "success";
        } else {
            LogT::create(['msg' => '签名失败']);
            exit('fail');
        }
    }

    //处理会员卡购买订单
    private function orderHandelForCard($pay_id, $order_num)
    {
        $order_info = UserCardT::getInfo($order_num);
        $update_res = UserCardT::update(['pay_id' => $pay_id], [
            'order_number' => $order_num
        ]);

        if (!$update_res) {
            LogT::create(['msg' => 'UserCardT更新支付状态失败，pay_id=' . $pay_id . 'order_id=' . $order_info->id]);
            exit('fail');
        }
        $res = (new CardService())->saveRecord($order_info->id, $order_info->u_id, $order_info->card->max, CommonEnum::CARD_ADD);
        if (!$res) {
            LogT::create(['msg' => 'UserCardT保存会员卡记录失败，pay_id=' . $pay_id . 'order_id=' . $order_info->id]);
            exit('fail');
        }


    }

    //处理非会员卡下单订单
    private function orderHandelForRecovery($pay_id, $order_num)
    {

        $order_info = OrderT::getInfo($order_num);
        $update_res = OrderT::update(['pay_id' => $pay_id], [
            'order_number' => $order_num
        ]);

        if (!$update_res) {
            LogT::create(['msg' => 'OrderT更新支付状态失败，pay_id=' . $pay_id . 'order_id=' . $order_info->id]);
            exit('fail');
        }
    }


}