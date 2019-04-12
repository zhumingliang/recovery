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
use app\api\model\SystemPriceT;
use app\api\model\UserCardT;
use app\api\model\UserCardV;
use app\api\model\UserT;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;

class OrderService
{
    /**
     * @param $params
     * @return array
     * @throws SaveException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function save($params)
    {
        $pay_way = $params['pay_type'];
        $params['u_id'] = Token::getCurrentUid();
        $params['order_number'] = makeOrderNo();
        $params['pay_id'] = CommonEnum::ORDER_STATE_INIT;
        $params['money'] = $pay_way == CommonEnum::PAY_CARD ? 0 : $this->getOrderMoney($params['count']);
        if (key_exists('phone', $params) && strlen($params['phone'])) {
            //保存用户手机号
            UserT::updateUserWithPhone($params['u_id'], $params['phone']);
        }
        $res = OrderT::create($params);
        if (!$res) {
            throw new SaveException();
        }

        if ($pay_way == CommonEnum::PAY_CARD) {
            $this->orderWithCard($params['count'], $res->id);
        }
        return [
            'msg' => 'success',
            'errorCode' => 0,
            'data' => [
                'id' => $res->id
            ]
        ];
    }

    private function orderWithCard($count, $o_id)
    {
        $u_id = Token::getCurrentUid();
        $this->checkCardBalance($u_id, $count, $o_id);

    }

    private function getOrderMoney($count)
    {
        $info = SystemPriceT::where('id', '=', 1)
            ->find();
        return ($info->price) * $count * 100;


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