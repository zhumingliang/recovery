<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/5/27
 * Time: 上午9:53
 */

namespace app\api\controller\v1;


use app\api\model\AdminT;
use app\api\model\TestT;
use app\api\service\AdminToken;
use app\api\service\AndroidToken;
use app\api\service\UserInfoService;
use app\api\service\WxTemplate;
use app\api\validate\TokenGet;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use think\Controller;
use think\facade\Cache;
use app\api\validate\User as userValidate;
use app\api\service\Token as tokenService;
use think\facade\Session;


class Token extends Controller
{
    /**
     * @api {GET} /api/v1/token/admin  1-CMS获取登陆token
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription  后台用户登录
     * @apiExample {post}  请求样例:
     *    {
     *       "phone": "18956225230",
     *       "pwd": "a123456"
     *     }
     * @apiParam (请求参数说明) {String} phone    用户手机号
     * @apiParam (请求参数说明) {String} pwd   用户密码
     *
     * @apiSuccessExample {json} 返回样例:
     * {"u_id":1,"username":"管理员","token":"bde274895aa23cff9462d5db49690452"}
     * @apiSuccess (返回参数说明) {int} u_id 用户id
     * @apiSuccess (返回参数说明) {int} username 管理员名称
     * @apiSuccess (返回参数说明) {String} token 口令令牌，每次请求接口需要传入，有效期 2 hours
     * @param $phone
     * @param $pwd
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function getAdminToken($phone, $pwd)
    {
        (new TokenGet())->scene('cms')->goCheck();
        $at = new AdminToken($phone, $pwd);
        $token = $at->get();
        return json($token);
    }

    /**
     * @api {GET} /api/v1/token/loginOut  2-CMS退出登陆
     * @apiGroup  CMS
     * @apiVersion 1.0.1
     * @apiDescription CMS退出当前账号登陆。
     * @apiExample {get}  请求样例:
     * http://recovery.mengant.cn/api/v1/token/loginOut
     * @apiSuccessExample {json} 返回样例:
     *{"msg":"ok","errorCode":0}
     * @apiSuccess (返回参数说明) {int} error_code 错误码： 0表示操作成功无错误
     * @apiSuccess (返回参数说明) {String} msg 信息描述
     *
     * @return \think\response\Json
     */
    public function loginOut()
    {
        $token = \think\facade\Request::header('token');
        Cache::rm($token);
        return json(new SuccessMessage());
    }

    /**
     * @api {GET} /api/v1/token/android  3-安卓获取登陆token
     * @apiGroup  Android
     * @apiVersion 1.0.1
     * @apiDescription  安卓设备登录
     * @apiExample {post}  请求样例:
     *    {
     *       "code": "23121"
     *     }
     * @apiParam (请求参数说明) {String} code  安卓设备唯一识别号
     * @apiSuccessExample {json} 返回样例:
     * {"u_id":2,"phone":null,"code":"1111","token":"3794a6247f8a4fed28239834b39ad4ba","card_type":1}
     * @apiSuccess (返回参数说明) {int} u_id 用户id
     * @apiSuccess (返回参数说明) {String} phone 用户手机号
     * @apiSuccess (返回参数说明) {int} card_type 会员卡类别：0：无会员；1 |青铜会员；2 | 金铜会员；3 | 黄金会员
     * @apiSuccess (返回参数说明) {String} token 口令令牌，每次请求接口需要传入，有效期 2天
     * @param $code
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function getAndroidToken($code)
    {
        (new TokenGet())->scene('android')->goCheck();
        $at = new AndroidToken($code);
        $token = $at->get();
        return json($token);
    }

}