<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-25
 * Time: 10:46
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\SystemPriceT;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UpdateException;

class SystemPrice extends BaseController
{

    /**
     * @api {POST} /api/v1/system/price/update 8-系统设置-单张照片恢复价格设置
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  单张照片恢复价格设置
     * @apiExample {post}  请求样例:
     *    {
     *       "id": 1,
     *       "price": 1000
     *     }
     * @apiParam (请求参数说明) {int} id  设置id
     * @apiParam (请求参数说明) {int} price 价格，单位分
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @return \think\response\Json
     * @throws UpdateException
     */
    public function update()
    {
        $params = $this->request->param();
        $id = SystemPriceT::update($params, ['id' => $params['id']]);
        if (!$id) {
            throw new UpdateException(['code' => 401,
                'msg' => '修改分类失败',
                'errorCode' => 120003
            ]);
        }
        return json(new  SuccessMessage());


    }

    /**
     * @api {GET} /api/v1/system/price 9-系统设置-获取单张照片恢复价格
     * @apiGroup  COMMON
     * @apiVersion 1.0.1
     * @apiDescription  获取单张照片恢复价格
     * @apiExample {get}  请求样例:
     * http://recovery.mengant.cn/api/v1/system/price
     * @apiSuccessExample {json} 返回样例:
     * {"id":1,"price":5}
     * @apiSuccess (返回参数说明) {int} id    设置id
     * @apiSuccess (返回参数说明) {int} price  会员价格 单位元
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function getInfo()
    {
        $info = SystemPriceT::where('id', '=', 1)
            ->hidden(['create_time', 'update_time'])
            ->find();
        return json($info);
    }

}