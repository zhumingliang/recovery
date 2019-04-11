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
            'body' => $this->orderType == CommonEnum::ORDER_RECOVERY ? '数据恢复数量:'.$this->payBody : '购买会员类别：'.$this->payBody,
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
       // return htmlspecialchars($response);
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
        //if ($order->u_id != Token::getCurrentUid()) {
        if ($order->u_id != 9) {
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
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");  //验证签名
        if ($flag) {
            //校验通知数据的正确性
            $out_trade_no = $_POST['out_trade_no'];    //商户订单号
            $trade_no = $_POST['trade_no'];    //支付宝交易号
            $trade_status = $_POST['trade_status'];    //交易状态trade_status
            $total_amount = $_POST['total_amount'];    //订单的实际金额
            $notify_id = $_POST['notify_id'];         //通知校验ID。
            $notify_time = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            $buyer_email = $_POST['buyer_email'];       //买家支付宝帐号；
            $passback_params = urldecode($_POST['passback_params']);       //买家支付宝帐号；
            $app_id = $_POST['app_id'];
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
                    "buyer_email" => $buyer_email,  //买家支付宝帐号；
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
            'order_num' => $order_num
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
            'order_num' => $order_num
        ]);

        if (!$update_res) {
            LogT::create(['msg' => 'OrderT更新支付状态失败，pay_id=' . $pay_id . 'order_id=' . $order_info->id]);
            exit('fail');
        }
    }


}