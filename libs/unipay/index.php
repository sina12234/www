<?php
/**
 * 订单校验测试请求样式：
 * <?xml version="1.0" encoding="UTF-8"?><checkOrderIdReq><orderid>000000000000000000000001</orderid><signMsg>3BDD679B092CEFBF723F198795CA67D7</signMsg><usercode>15600000000</usercode><provinceid>00001</provinceid><cityid>00001</cityid></checkOrderIdReq>
 * 
 * 支付结果通知请求样式：
 * 1. 支付失败
 * <?xml version="1.0" encoding="UTF-8"?><callbackReq><orderid>000000000000000000000001</orderid><ordertime>20160630120000</ordertime><cpid>test</cpid><appid>test</appid><fid>test</fid><consumeCode>9999999999999999999999999999</consumeCode><payfee>1000</payfee><payType>4</payType><hRet>1</hRet><status>00100</status><signMsg>BB2F5AEE289C68A1F1D0C390EF6944C4</signMsg></callbackReq>
 * 
 * 2. 支付成功
 * <?xml version="1.0" encoding="UTF-8"?><callbackReq><orderid>000000000000000000000001</orderid><ordertime>20160630120000</ordertime><cpid>test</cpid><appid>test</appid><fid>test</fid><consumeCode>9999999999999999999999999999</consumeCode><payfee>1000</payfee><payType>4</payType><hRet>0</hRet><status>00000</status><signMsg>0468FD4384E7C6FDDAFC76AE4F614D35</signMsg></callbackReq>
 * 
 */
header('Content_Type: application/xml;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');

/**
 * 此类中的参数都可在开发者社区获取
 * 请替换为正式参数
 */
class App {
    //沃商店CPID
    public static $CPID = "test";
    
    //计费密钥
    public static $KEY = "test";
    
    //沃商店应用id
    public static $APP_ID = "test";
    
    //应用名称
    public static $APP_NAME = "test";
    
    //开发者名称
    public static $APP_DEVELOPER = "test";
    
    //渠道号
    public static $CHANNEL_ID = "test";
}

class UnicomUtils {
    static function getRequestBean() {
        $bean = simplexml_load_string(file_get_contents('php://input'));
        $request = array();
        foreach($bean as $key => $value){
            $request[(string)$key] = (string) $value;
        }
        return $request;
    }

    static function toResponse($params, $rootName = 'xml') {
        
        if (gettype($params) == 'array') {
            $entryXml = "";
            foreach ($params as $key => $value) {
                $entryXml = "${entryXml}<${key}>$value</${key}>";
            }
            return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><${rootName}>${entryXml}</${rootName}>";
        }
        if (!is_null($params)) {
            return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><${rootName}>${params}</${rootName}>";
        }
        return '';
    }
 }

class UnicomPayService {
    
    public function processValidateOrderId() {
         $request = UnicomUtils::getRequestBean();
        $orderid = $request['orderid'];
        $signMsg = $request['signMsg'];
        $usercode = $request['usercode'];
        $provinceid = $request['provinceid'];
        $cityid = $request['cityid'];
        
        $mySign = md5("orderid=${orderid}&Key=" . App::$KEY);

        $response = array();

        //验证签名是否一致
        if (strcasecmp($mySign, $signMsg) == 0) {
            //TODO: start 填写校验订单逻辑，需要开发者完成 
            
            /*
             * 如果通过校验，将ifpasswd设置为true
             * 通过校验的含义为，待校验的订单在开发者系统中为有效订单，可以继续支付
             */
            $ifpasswd = true;
            //TODO: end
            
            if ($ifpasswd) {
                //TODO: 通过订单获取必要信息，需开发者完成
                $serviceid = "";
                $feename = "";
                $payfee = 0;
                $ordertime = date("YmdHis");
                $gameaccount = "";
                $macaddress = "";
                $ipaddress = "";
                $imei = "";
                $appversion = "";
                //

                //0-验证成功 1-验证失败，必填
                $response["checkOrderIdRsp"] = '0';

                //应用名称，必填
                $response["appName"] = App::$APP_NAME;

                //计费点名称
                $response["feename"] = $feename;

                //计费点金额，单位分
                $response["payfee"] = $payfee;

                //应用开发商名称，必填
                $response["appdeveloper"] = App::$APP_DEVELOPER;

                //游戏账号，长度<=64，联网支付必填
                $response["gameaccount"] = $gameaccount;

                //MAC地址去掉冒号，联网支付必填，单机尽量上报
                $response["macaddress"] = $macaddress;

                //沃商店应用id，必填
                $response["appid"] = App::$APP_ID;

                //IP地址，去掉点号，补零到每地址段3位, 如：192168000001，联网必填，单机尽量
                $response["ipaddress"] = $ipaddress;

                //沃商店计费点，必填
                $response["serviceid"] = $serviceid;

                //渠道ID，必填
                $response["channelid"] = App::$CHANNEL_ID;

                //沃商店CPID，必填
                $response["cpid"] = App::$CPID;

                //订单时间戳，14位时间格式，联网必填，单机尽量
                $response["ordertime"] = $ordertime;

                //设备标识，联网必填，单机尽量上报
                $response["imei"] = $imei;

                //应用版本号，必填
                $response["appversion"] = $appversion;
            }
            
        } else {
            $response['checkOrderIdRsp'] = '1';
        }

        echo UnicomUtils::toResponse($response, "paymessages");
     }

    public function processPayNotify() {
         //订单是否被处理
        $fullyProcessed = false;
        
        //解析http请求体
        $request = UnicomUtils::getRequestBean();
        
        //cp订单号
        $orderid = $request["orderid"];
        //订单时间
        $ordertime = $request["ordertime"];
        //沃商店cpid
        $cpid = $request["cpid"];
        //应用ID
        $appid = $request["appid"];
        //渠道ID
        $fid = $request["fid"];
        //计费点ID
        $consumeCode = $request["consumeCode"];
        //支付金额，单位分
        $payfee = $request["payfee"];
        //0-沃支付，1-支付宝，2-VAC支付，3-神州付 ...
        $payType = $request["payType"];
        //支付结果，0代表成功，其他代表失败
        $hRet = $request["hRet"];
        //状态码
        $status = $request["status"];
        //签名 MD5(orderid=XXX&ordertime=XXX&cpid=XXX&appid=XXX&fid=XXX&consumeCode=XXX&payfee=XXX&payType=XXX&hRet=XXX&status=XXX&Key=XXX)
        $signMsg = $request["signMsg"];
        
       //校验签名是否正确
        $mySign = md5("orderid=${orderid}&ordertime=${ordertime}&cpid=${cpid}&appid=${appid}&fid=${fid}&consumeCode=${consumeCode}&payfee=${payfee}&payType=${payType}&hRet=${hRet}&status=${status}&Key=" . App::$KEY);

        if (strcasecmp($mySign, $signMsg) == 0) {
            //TODO： start 开发者处理逻辑, 处理完成后，将$fullyProcessed设为ture
            
            if ("0" == $hRet) {
                //TODO: 添加支付成功逻辑
            } else {
                //TODO: 添加支付失败逻辑
            }
            //
            $fullyProcessed = true;
            //
            //TODO: end
            
        }
        
        if (!$fullyProcessed) {
            header("HTTP/1.0 400 Bad Request");
        }

        echo UnicomUtils::toResponse($fullyProcessed ? 1 : 0, "callbackRsp");
    }
}

$serviceid = $_GET['serviceid'];
$payService = new UnicomPayService();

if ($serviceid == 'validateorderid') {
    $payService->processValidateOrderId();
} else if (is_null($serviceid)) {
    $payService->processPayNotify();
}
?>