<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-24
 * Time: 10:35
 */

namespace app\api\controller\v1;


use app\api\model\CardT;
use app\api\service\CardService;
use app\api\validate\CardValidate;
use app\lib\enum\CommonEnum;
use app\lib\exception\DeleteException;
use app\lib\exception\SaveException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UpdateException;
use think\Controller;

class Card extends Controller
{

    /**
     * @api {POST}  /api/v1/card/save 4-会员卡管理-新增会员卡
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  会员卡管理-新增会员卡
     * @apiExample {post}  请求样例:
     *    {
     *       "type": 1,
     *       "price": 1000,
     *       "max": 20
     *     }
     * @apiParam (请求参数说明) {int} type  会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员
     * @apiParam (请求参数说明) {int} price  会员价格 单位分
     * @apiParam (请求参数说明) {int} max   最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     * @return \think\response\Json
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function save()
    {
        \app\api\service\Token::getCurrentUid();
        (new CardValidate())->scene('save')->goCheck();
        $params = $this->request->param();
        $params['state'] = CommonEnum::STATE_IS_OK;
        $params['min'] = 0;
        $id = CardT::create($params);
        if (!$id) {
            throw  new SaveException();
        }
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/card/handel  5-会员卡管理-删除会员卡
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  删除会员卡
     * @apiExample {POST}  请求样例:
     * {
     * "id": 1,
     * }
     * @apiParam (请求参数说明) {int} id 会员卡id
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     *
     */
    public function handel()
    {

        (new CardValidate())->scene('id')->goCheck();
        $params = $this->request->param();
        $id = CardT::update(['state' => CommonEnum::STATE_IS_FAIL], ['id' => $params['id']]);
        if (!$id) {
            throw new DeleteException();
        }
        return json(new SuccessMessage());

    }

    /**
     * @api {POST} /api/v1/card/update 6-会员卡管理-修改会员卡
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  修改会员卡
     * @apiExample {post}  请求样例:
     *    {
     *       "id": 1,
     *       "type": 1,
     *       "price": 1000,
     *       "max": 20
     *     }
     * @apiParam (请求参数说明) {int} id    分类id
     * @apiParam (请求参数说明) {int} type  会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员
     * @apiParam (请求参数说明) {int} price  会员价格 单位分
     * @apiParam (请求参数说明) {int} max   最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @return \think\response\Json
     * @throws UpdateException
     * @throws \app\lib\exception\ParameterException
     */
    public function update()
    {
        (new CardValidate())->scene('update')->goCheck();
        $params = $this->request->param();
        $id = CardT::update($params, ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException(['code' => 401,
                'msg' => '修改分类失败',
                'errorCode' => 120003
            ]);

        }
        return json(new  SuccessMessage());


    }

    /**
     * @api {GET} /api/v1/card/list 7-会员卡管理-获取会员卡列表
     * @apiGroup  COMMON
     * @apiVersion 1.0.1
     * @apiDescription  获取会员卡列表
     * @apiExample {get}  请求样例:
     * http://recovery.mengant.cn/api/v1/card/list
     * @apiSuccessExample {json} 返回样例:
     * [{"id":1,"min":0,"max":20,"price":2990,"state":1,"create_time":"2019-01-24 11:18:31","update_time":"2019-01-24 11:18:31","type":1}]
     * @apiSuccess (返回参数说明) {int} id    分类id
     * @apiSuccess (返回参数说明) {int} type  会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员
     * @apiSuccess (返回参数说明) {int} price  会员价格 单位分
     * @apiSuccess (返回参数说明) {int} max   最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $list = CardT::where('state', '=', CommonEnum::STATE_IS_OK)
            ->hidden(['state', 'create_time', 'update_time'])
            ->select();
        return json($list);
    }

    /**
     * @api {POST} /api/v1/card/order/save  10-新增用户购买会员支付订单
     * @apiGroup  Android
     * @apiVersion 1.0.1
     * @apiDescription  新增用户购买会员支付订单
     * @apiExample {post}  请求样例:
     *    {
     *       "c_id": 1,
     *       "money": 500
     *       "pay_type": 1
     *     }
     * @apiParam (请求参数说明) {int} c_id  会员卡id
     * @apiParam (请求参数说明) {int} money  金额
     * @apiParam (请求参数说明) {int} pay_type  支付类别：1 | 微信支付；2 | 支付宝支付
     *
     * @apiSuccessExample {json} 返回样例:
     * {"id": 1}
     * @apiSuccess (返回参数说明) {int} id 订单id
     * @return \think\response\Json
     * @throws SaveException
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function saveOrder()
    {
        (new CardValidate())->scene('order')->goCheck();
        $params = $this->request->param();
        $id = (new CardService())->saveOrder($params);
        return json(['id' => $id]);


    }


}