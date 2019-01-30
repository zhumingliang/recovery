<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-01-23
 * Time: 23:06
 */

namespace app\api\service;


use app\api\model\UserCardT;
use app\api\model\UserT;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;

class AndroidToken extends Token
{

    protected $code;


    function __construct($code)
    {
        $this->code = $code;
    }


    public function get()
    {
        try {
            $admin = UserT::where('code', '=', $this->code)
                ->find();

            if (is_null($admin)) {
                $admin = UserT::create([
                    'code' => $this->code,
                    'phone' => ''
                ]);
            }
            /**
             * 获取缓存参数
             */
            $cachedValue = $this->prepareCachedValue($admin);
            /**
             * 缓存数据
             */
            $token = $this->saveToCache('', $cachedValue);
            $token['card_type'] = (new CardService())->getUserCardInfo($admin->id);
            return $token;

        } catch (Exception$e) {
            throw $e;
        }

    }


    /**
     * @param $key
     * @param $cachedValue
     * @return mixed
     * @throws TokenException
     */
    private function saveToCache($key, $cachedValue)
    {
        $key = empty($key) ? self::generateToken() : $key;
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = Cache::remember($key, $value, $expire_in);


        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 20002
            ]);
        }

        $cachedValue['token'] = $key;
        return $cachedValue;
    }

    private function prepareCachedValue($admin)
    {

        $cachedValue = [
            'u_id' => $admin->id,
            'phone' => $admin->phone,
            'code' => $admin->code,
        ];

        return $cachedValue;
    }


}