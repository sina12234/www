<?php

class utility_iap
{
    /**
     * 21000 App Store不能读取你提供的JSON对象
     * 21002 receipt-data域的数据有问题
     * 21003 receipt无法通过验证
     * 21004 提供的shared secret不匹配你账号中的shared secret
     * 21005 receipt服务器当前不可用
     * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
     * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
     * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
     *
     * @param $receipt
     * @param bool $isSandbox
     * @return array
     * @throws Exception
     * @link https://developer.apple.com/library/ios/releasenotes/General/ValidateAppStoreReceipt/Chapters/ValidateRemotely.html#//apple_ref/doc/uid/TP40010573-CH104-SW1
     */
    public static function getReceiptData($receipt, $isSandbox = false)
    {
        if ($isSandbox) {
            $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
        } else {
            $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';
        }

        $postData = json_encode(
            ['receipt-data' => $receipt]
        );

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  //这两行一定要加，不加会报SSL 错误
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);
        $errNo    = curl_errno($ch);
        $errMsg   = curl_error($ch);
        curl_close($ch);

        //判断时候出错，抛出异常
        if ($errNo != 0) {
            throw new Exception($errMsg, $errNo);
        }

        $data = json_decode($response);
        //判断返回的数据是否是对象
        if (!is_object($data)) {
            throw new Exception('Invalid response data', 3003);
        }
        //判断购买时候成功
        if (!isset($data->status) || $data->status != 0) {
            throw new Exception('Invalid receipt', $data->status);
        }

        //返回产品的信息
        return $data;
    }
}
