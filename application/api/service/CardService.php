<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-28
 * Time: 10:55
 */

namespace app\api\service;


use app\api\model\CardRecordT;
use app\api\model\UserCardT;
use app\api\model\UserCardV;
use app\lib\enum\CommonEnum;
use app\lib\exception\SaveException;

class CardService
{

    /**
     * 获取指定用户会员信息
     * @param $u_id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserCardInfo($u_id)
    {
        $card = UserCardT::where('u_id', $u_id)
            ->where('pay_id', '>', 0)
            ->order('create_time desc')
            ->find();
        if (!$card) {
            return 0;
        }

        return $card->card->type;
    }

    /**
     * 新增订单
     * @param $params
     * @return mixed
     * @throws SaveException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function saveOrder($params)
    {
        $params['u_id'] = Token::getCurrentUid();
        $params['order_number'] = makeOrderNo();
        $params['pay_id'] = 0;
        $res = UserCardT::create($params);
        if (!$res) {
            throw  new SaveException();
        }
        return $res->id;

    }

    /**
     * 保存会员卡消费记录
     * @param $u_id
     * @param $count
     * @param $type
     * @param $from_id
     * @return CardRecordT
     */
    public function saveRecord($from_id, $u_id, $count, $type)
    {
        $count = $type == CommonEnum::CARD_REDUCE ? 0 - $count : $count;
        $data = [
            'u_id' => $u_id,
            'from_id' => $from_id,
            'count' => $count,
            'type' => $type,
        ];
        return CardRecordT::create($data);


    }

    public function userCheck()
    {
        $u_id = Token::getCurrentUid();
        $card = UserCardV::where('u_id', $u_id)
            ->where('pay_id', '>', 0)
            ->order('create_time desc')
            ->find();
        if (!$card) {
            return [
                'msg' => 'success',
                'errorCode' => 0,
                'data' => [
                    'isCard' => 0,
                    'type' => 0,
                    'balance' => 0
                ]
            ];
        }
        //获取会员余额
        $balance = CardRecordT::getBalance($u_id);
        return [
            'msg' => 'success',
            'errorCode' => 0,
            'data' => [
                'isCard' => 1,
                'type' => $card->type,
                'balance' => $balance
            ]
        ];
    }


}