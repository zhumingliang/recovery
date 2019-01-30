<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-29
 * Time: 10:59
 */

namespace app\api\service;


use app\api\model\CardRecordT;
use app\api\model\OrderT;
use app\api\model\UserCardT;
use app\api\model\UserCardV;
use app\api\model\UserT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;

class OrderService
{
    /**
     *保存订单
     * @param $params
     * @return SuccessMessage|array
     * @throws SaveException
     */
    public function save($params)
    {
        $params['u_id'] = Token::generateToken();
        $params['pay_id'] = CommonEnum::ORDER_STATE_INIT;
        if (key_exists('phone', $params) && strlen($params['phone'])) {
            //保存用户手机号
            UserT::updateUserWithPhone($params['u_id'], $params['phone']);
        }
        $res = OrderT::create($params);
        if (!$res) {
            throw new SaveException();
        }
        $pay_way = $params['pay_way'];
        if ($pay_way == CommonEnum::PAY_CARD) {
            return $this->orderWithCard($params['count'], $res->id);
        } else {
            //处理金额订单
            return ['id' => $res->id];
        }
    }

    private function orderWithCard($count, $o_id)
    {
        $u_id = Token::getCurrentUid();
        $this->checkCardBalance($u_id, $count, $o_id);
        return new SuccessMessage();

    }


    private function checkCardBalance($u_id, $count, $o_id)
    {
        $card = UserCardV::where('u_id', $u_id)
            ->where('pay_id', '>', 0)
            ->order('create_time desc')
            ->find();
        if (!$card) {
            throw new SaveException([
                'msg' => '该用户没有会员卡信息',
                'errorCode' => 40009
            ]);
        }
        if ($card->type != 3) {
            $balance = CardRecordT::getBalance($u_id);
            if ($balance - $count < 0) {
                throw new SaveException([
                    'msg' => '会员卡余额不足',
                    'errorCode' => 40010
                ]);
            }
        }

        $res = (new CardService())->saveRecord($o_id, $u_id, $count, CommonEnum::CARD_REDUCE);
        if (!$res) {
            throw new SaveException([
                'msg' => '保存会员卡消费记录失败',
                'errorCode' => 40011
            ]);
        }
        return true;

    }

}