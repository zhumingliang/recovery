define({ "api": [  {    "type": "GET",    "url": "/api/v1/pay/getPreOrder",    "title": "11-Android端获取支付数据",    "group": "Android",    "version": "1.0.1",    "description": "<p>Android端获取支付数据（购买会员卡；直接支付恢复订单）</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://recovery.mengant.cn/api/v1/pay/getPreOrder?id=1&type=1",        "type": "get"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>订单id</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "type",            "description": "<p>订单类别：1 | 购买会员卡支付；2 | 直接支付恢复数据订单</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "支付宝返回样例:",          "content": "{\n{\"pay_params\":\"alipay_sdk=alipay-sdk-php-20180705&app_id=2019011162869266&biz_content=%7B%22body%22%3A%22%5Cu652f%5Cu4ed8%5Cu4f1a%5Cu5458%5Cu5361%22%2C%22subject%22%3A%22%5Cu652f%5Cu4ed8%5Cu4f1a%5Cu5458%5Cu5361%22%2C%22out_trade_no%22%3A%22C128875414239899%22%2C%22timeout_express%22%3A%2290m%22%2C%22total_amount%22%3A10%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22passback_params%22%3A%221%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&notify_url=recovery.mengant.cn%2Fapi%2Fv1%2Fpay%2Falipay%2Fnotify&sign_type=RSA2&timestamp=2019-01-28+23%3A02%3A59&version=1.0&sign=IBfzVcIRWU65JwcAKfh4oUh4fGybP3rpFStdmCjj7w9Jqc2jNy5HefWtyp5lgWFwuU4bFwIpZn0hoYjPhe2uACZNmJSIU1xMAHnHs%2BTrAi%2Bp84ihTnlrvMhOVhVXfRFb0VQw7BgHM9QSl1Bo%2FUVow3wQMbkHohg7wvc3sOkxJtbS1%2BsQa%2FkL2rQp3XdnylOBu2w6Hz5OFTSm7hcpVaQGbzPvQ7Nt13LGkta%2FTIs5GTPEqfz34k32vybYSF7wkJYFW3Q2%2B%2BBgw%2B%2FjozG%2Bch9zQRjPI8BbRavplK1l8jPh8PSokdrnzHOj9G0InZanJzz3YWIr46tZucysyBJSGym1%2Bg%3D%3D\"}     * }",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "pay_params",            "description": "<p>安卓支付所需数据</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Pay.php",    "groupTitle": "Android",    "name": "GetApiV1PayGetpreorder"  },  {    "type": "GET",    "url": "/api/v1/token/android",    "title": "3-安卓获取登陆token",    "group": "Android",    "version": "1.0.1",    "description": "<p>安卓设备登录</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"code\": \"23121\"\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "code",            "description": "<p>安卓设备唯一识别号</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"u_id\":2,\"phone\":null,\"code\":\"1111\",\"token\":\"3794a6247f8a4fed28239834b39ad4ba\",\"card_type\":1}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "u_id",            "description": "<p>用户id</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "phone",            "description": "<p>用户手机号</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "card_type",            "description": "<p>会员卡类别：0：无会员；1 |青铜会员；2 | 金铜会员；3 | 黄金会员</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "token",            "description": "<p>口令令牌，每次请求接口需要传入，有效期 2天</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Token.php",    "groupTitle": "Android",    "name": "GetApiV1TokenAndroid"  },  {    "type": "POST",    "url": "/api/v1/card/order/save",    "title": "10-新增用户购买会员支付订单",    "group": "Android",    "version": "1.0.1",    "description": "<p>新增用户购买会员支付订单</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"c_id\": 1,\n   \"money\": 500\n   \"pay_type\": 1\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "c_id",            "description": "<p>会员卡id</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "money",            "description": "<p>金额</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "pay_type",            "description": "<p>支付类别：1 | 微信支付；2 | 支付宝支付</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"id\": 1}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>订单id</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Card.php",    "groupTitle": "Android",    "name": "PostApiV1CardOrderSave"  },  {    "type": "POST",    "url": "/api/v1/order/save",    "title": "12-Android端新增恢复数据订单",    "group": "Android",    "version": "1.0.1",    "description": "<p>Android端新增恢复数据订单</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"pay_type\": 1,\n   \"count\": 1000,\n   \"money\": 20\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "pay_type",            "description": "<p>支付类别：1 |微信；2 | 支付宝；3 | 会员卡</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "count",            "description": "<p>恢复数量</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "money",            "description": "<p>支付金额</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\": \"ok\",\"error_code\": 0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误代码 0 表示没有错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>操作结果描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Order.php",    "groupTitle": "Android",    "name": "PostApiV1OrderSave"  },  {    "type": "GET",    "url": "/api/v1/token/admin",    "title": "1-CMS获取登陆token",    "group": "CMS",    "version": "1.0.1",    "description": "<p>后台用户登录</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"phone\": \"18956225230\",\n   \"pwd\": \"a123456\"\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "phone",            "description": "<p>用户手机号</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "pwd",            "description": "<p>用户密码</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"u_id\":1,\"username\":\"管理员\",\"token\":\"bde274895aa23cff9462d5db49690452\"}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "u_id",            "description": "<p>用户id</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "username",            "description": "<p>管理员名称</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "token",            "description": "<p>口令令牌，每次请求接口需要传入，有效期 2 hours</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Token.php",    "groupTitle": "CMS",    "name": "GetApiV1TokenAdmin"  },  {    "type": "GET",    "url": "/api/v1/token/loginOut",    "title": "2-CMS退出登陆",    "group": "CMS",    "version": "1.0.1",    "description": "<p>CMS退出当前账号登陆。</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://recovery.mengant.cn/api/v1/token/loginOut",        "type": "get"      }    ],    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\":\"ok\",\"errorCode\":0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误码： 0表示操作成功无错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>信息描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Token.php",    "groupTitle": "CMS",    "name": "GetApiV1TokenLoginout"  },  {    "type": "POST",    "url": "/api/v1/card/handel",    "title": "5-会员卡管理-删除会员卡",    "group": "CMS",    "version": "1.0.1",    "description": "<p>删除会员卡</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n\"id\": 1,\n}",        "type": "POST"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>会员卡id</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\": \"ok\",\"error_code\": 0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误代码 0 表示没有错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>操作结果描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Card.php",    "groupTitle": "CMS",    "name": "PostApiV1CardHandel"  },  {    "type": "POST",    "url": "/api/v1/card/save",    "title": "4-会员卡管理-新增会员卡",    "group": "CMS",    "version": "1.0.1",    "description": "<p>会员卡管理-新增会员卡</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"type\": 1,\n   \"price\": 1000,\n   \"max\": 20\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "type",            "description": "<p>会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "price",            "description": "<p>会员价格 单位分</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "max",            "description": "<p>最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\": \"ok\",\"error_code\": 0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误代码 0 表示没有错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>操作结果描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Card.php",    "groupTitle": "CMS",    "name": "PostApiV1CardSave"  },  {    "type": "POST",    "url": "/api/v1/card/update",    "title": "6-会员卡管理-修改会员卡",    "group": "CMS",    "version": "1.0.1",    "description": "<p>修改会员卡</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"id\": 1,\n   \"type\": 1,\n   \"price\": 1000,\n   \"max\": 20\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>分类id</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "type",            "description": "<p>会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "price",            "description": "<p>会员价格 单位分</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "max",            "description": "<p>最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\": \"ok\",\"error_code\": 0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误代码 0 表示没有错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>操作结果描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Card.php",    "groupTitle": "CMS",    "name": "PostApiV1CardUpdate"  },  {    "type": "POST",    "url": "/api/v1/system/price/update",    "title": "8-系统设置-单张照片恢复价格设置",    "group": "CMS",    "version": "1.0.1",    "description": "<p>单张照片恢复价格设置</p>",    "examples": [      {        "title": "请求样例:",        "content": "{\n   \"id\": 1,\n   \"price\": 1000\n }",        "type": "post"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>设置id</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "price",            "description": "<p>价格，单位分</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\": \"ok\",\"error_code\": 0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误代码 0 表示没有错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>操作结果描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/SystemPrice.php",    "groupTitle": "CMS",    "name": "PostApiV1SystemPriceUpdate"  },  {    "type": "GET",    "url": "/api/v1/card/list",    "title": "7-会员卡管理-获取会员卡列表",    "group": "COMMON",    "version": "1.0.1",    "description": "<p>获取会员卡列表</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://recovery.mengant.cn/api/v1/card/list",        "type": "get"      }    ],    "success": {      "examples": [        {          "title": "返回样例:",          "content": "[{\"id\":1,\"min\":0,\"max\":20,\"price\":2990,\"state\":1,\"create_time\":\"2019-01-24 11:18:31\",\"update_time\":\"2019-01-24 11:18:31\",\"type\":1}]",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>分类id</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "type",            "description": "<p>会员卡类别：1 |青铜会员；2 | 金铜会员；3 | 黄金会员</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "price",            "description": "<p>会员价格 单位分</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "max",            "description": "<p>最大恢复数量 ：type=3 时 默认为0 ；0 表示无上限</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Card.php",    "groupTitle": "COMMON",    "name": "GetApiV1CardList"  },  {    "type": "GET",    "url": "/api/v1/system/price",    "title": "9-系统设置-获取单张照片恢复价格",    "group": "COMMON",    "version": "1.0.1",    "description": "<p>获取单张照片恢复价格</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://recovery.mengant.cn/api/v1/system/price",        "type": "get"      }    ],    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"id\":1,\"price\":500}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "id",            "description": "<p>设置id</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "price",            "description": "<p>会员价格 单位分</p>"          }        ]      }    },    "filename": "application/api/controller/v1/SystemPrice.php",    "groupTitle": "COMMON",    "name": "GetApiV1SystemPrice"  }] });
