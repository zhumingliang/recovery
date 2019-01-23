<?php

namespace app\api\controller\v1;

use think\Controller;

//require '../../../../extend/alipay/aop/AopClient.php';
require __DIR__ . '/../../../../extend/alipay/aop/AopClient.php';
require __DIR__ . '/../../../../extend/alipay/aop/request/AlipayTradeAppPayRequest.php';

class Index extends Controller
{
    public function index()
    {

        $aop = new \AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = "2019011162869266";
        $aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEArrmQ/xz65cdOlAtlcvUcxR/wYdNRQ1PIuqrhipV9AOMnlKDrd91wWpQVmaKWO4HLJ/A9Z0kjbDyF5NL1Dyy4ajjpNP9xRjNHzz3wXqULr2zBWWYcUc391DE78Twmozoof5uVZ6WevRlU3p3R4ubV7Y4Cl+lsxtGAtfm70X+qcd/ROol3vJ2RI854sio1r3l3NN1YQ/H7jsYrcdsDC3Patd/qGUadS1FLHpWsX+6JBFSZuPE5cYsTX8h9dO1UX+uy1mDNAGjOL3h+6OGJsmXssz/5kgJuVx56bPFZEvlZfzbz9qAgwfEa73zNiOyZWEOJaQUPaq66Vpt5o0nD5NrBHwIDAQABAoIBAGIyKCs4PkmlGaRaQ0m4N9MRgCd6e9E65TUDlH4uRXKaN0Rwq+VRrjM5xmnx1jzbVTG88sV5dU7/NMDFTrSYjYwlL+t5JFAvwcvXI6ANYePVW1TC7meLPXxjryyEgbJ6nQgaiH0Xt37PHcN/LtY2pUQ863g0181lMNEU6Vl0RnZ4RR3fQSur3anG8T4GCXlniLNUW/+r3Hh5oDnA9QSOXMha+VlJYghgcqDxnSqXvdtZ4UhsdqUwypz6NLNhUeibuBrQhJY8wM4fvKMDlzMASDD9MVKOR5Ut0GpQmmShGtdpg0DLvnxOVFo6RmIVU6ILHULJ8k9bWsvh1yReoLl4jQECgYEA4C6/LMQf1Uo+PQY//tCCcG+6X14fwbc813zbGjoY3+X+RJw+tIE4RDegy4EPOaob5OGoxUQvWBtDOD3PRoW5xaBgqzGorrIN70Nr95pSLyQM7+wP4L/CYNTqhev3/ZwLcDiltOAhwQQ69shbOMw+PpXrrL9VqfZSvnopwwUfnWsCgYEAx4Xdpk6ymo8+pCwWM1DyHmG8xu//Zy88pTnwrrqlQdO4Q+3/tmZwP5vALdpHV3L3j+dsfdc7XxePJ/wGOArPK1SiRudt5EB4f50BgpcirPsIilE1w2RHJ3A1DfHhHMYA2RPy3UIG1ttdMRQA4PDIk+cRAmj5IeWIxAOboYNAxB0CgYEAmla0mR5BCDd/18V4w3ZLhxr0hXmohVYglf5IXasHuwV9Y0DqTpzz3sspcVFd6QY7A/1zkrPZoF/MjGjJTbT865j5VILwray5uTKCn0loMToxie/MEqASM1YCS8bjyX7nxQ/KzVbNBjRiX8oaO2UpqS7PvPNRB50k5Cv4mmHpnRcCgYEAq06fgSP+qz+kC/ciVIsCZt2J5NC43BItors9j/gG3Z73jbNxo10OsT574aKRMF8weSi8+MrsWffzXORBPqrhNxXigrg/nQNRslfOrR4wko6yxXd2jr2xbgYkN0Xe/wJuRfOfySLglmcf+3xuSLPTs1FoREZ26QiXxZxHaoQz/m0CgYBIwbSPT8bMuVvF8BZPcdDfZoHbmoNl++3Hv1w6IQFSTpqjEGpY3xMp492IN8D3hXzT2cVc2ObIhWHqk88iIrH9sHp2jqV4F3zyX66g0xAsJVqbifBXByqYLjZ6llP6K/xft2R3rhR8zUkaFpEvQnMqogLJ7A9AT9fdR5rPIMhdrg==';
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArrmQ/xz65cdOlAtlcvUcxR/wYdNRQ1PIuqrhipV9AOMnlKDrd91wWpQVmaKWO4HLJ/A9Z0kjbDyF5NL1Dyy4ajjpNP9xRjNHzz3wXqULr2zBWWYcUc391DE78Twmozoof5uVZ6WevRlU3p3R4ubV7Y4Cl+lsxtGAtfm70X+qcd/ROol3vJ2RI854sio1r3l3NN1YQ/H7jsYrcdsDC3Patd/qGUadS1FLHpWsX+6JBFSZuPE5cYsTX8h9dO1UX+uy1mDNAGjOL3h+6OGJsmXssz/5kgJuVx56bPFZEvlZfzbz9qAgwfEa73zNiOyZWEOJaQUPaq66Vpt5o0nD5NrBHwIDAQAB';
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = "{\"body\":\"我是测试数据\","
            . "\"subject\": \"App支付测试\","
            . "\"out_trade_no\": \"20180125test01\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"0.01\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl("recovery.mengant.cn/api/v1/index/notify");
        $request->setBizContent($bizcontent);
//这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
    }

    public function notify()
    {
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArrmQ/xz65cdOlAtlcvUcxR/wYdNRQ1PIuqrhipV9AOMnlKDrd91wWpQVmaKWO4HLJ/A9Z0kjbDyF5NL1Dyy4ajjpNP9xRjNHzz3wXqULr2zBWWYcUc391DE78Twmozoof5uVZ6WevRlU3p3R4ubV7Y4Cl+lsxtGAtfm70X+qcd/ROol3vJ2RI854sio1r3l3NN1YQ/H7jsYrcdsDC3Patd/qGUadS1FLHpWsX+6JBFSZuPE5cYsTX8h9dO1UX+uy1mDNAGjOL3h+6OGJsmXssz/5kgJuVx56bPFZEvlZfzbz9qAgwfEa73zNiOyZWEOJaQUPaq66Vpt5o0nD5NrBHwIDAQAB';
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");  //验证签名
        if ($flag) {
            //校验通知数据的正确性
            $out_trade_no = $_POST['out_trade_no'];    //商户订单号
    $trade_no = $_POST['trade_no'];    //支付宝交易号
    $trade_status = $_POST['trade_status'];    //交易状态trade_status
    $total_amount = $_POST['total_amount'];    //订单的实际金额
    $app_id = $_POST['app_id'];
    if ($app_id != $this->config['app_id']) exit('fail');    //验证app_id是否为该商户本身
    //只有交易通知状态为TRADE_SUCCESS或TRADE_FINISHED时，支付宝才会认定为买家付款成功。
    if ($trade_status != 'TRADE_FINISHED' && $trade_status != 'TRADE_SUCCESS')
        exit('fail');
    //校验订单的正确性
    if (!empty($out_trade_no)) {
        //1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
        //2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
        //3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）。
        //上述1、2、3有任何一个验证不通过，则表明本次通知是异常通知，务必忽略。在上述验证通过后商户必须根据支付宝不同类型的业务通知，正确的进行不同的业务处理，并且过滤重复的通知结果数据。
        //校验成功后在response中返回success，校验失败返回failure
    }
    exit('fail');
}
        echo "fail"; //验证签名失败


    }

}
