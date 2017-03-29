<?php

class user_login
{
    private static $_map = [
        'favTeacher' => 'addFavTeacher', // 一键登录喜欢老师
    ];

    public static $errors = [];

    /**
     * @param $msg
     * @param $code
     */
    public static function setError($msg, $code)
    {
        self::$errors['code'] = $code;
        self::$errors['msg']  = $msg;
    }

    /**
     * @return array
     */
    public static function getErrors()
    {
        return self::$errors;
    }

    /**
     * @desc login by mobile
     *
     * @param $name
     * @param $password
     * @param bool|false $forever
     * @return bool
     */
    public static function doLogin($name, $password, $forever = false)
    {
        if (empty($name)) {
            self::setError('请输入账号', 1);
            return false;
        }

        if (empty($password)) {
            self::setError('请输入密码', 2);
            return false;
        }

        if (utility_valid::mobile($name) === false) {
            self::setError('手机号码格式不正确', 3);
            return false;
        }

        if (user_api::isRegister($name === false)) {
            self::setError('该手机号未注册', 4);
            return false;
        }

        $forever && $forever = true;

        if (user_api::login($name, $password, $forever)) {
            return true;
        }

        self::setError('登录失败', 6);

        return false;
    }

    /**
     * @param $source
     * @param array $params
     * @return bool|mixed
     */
    public static function action($source, array $params)
    {
        if (!array_key_exists($source, self::$_map)) {
            self::setError('非法登陆', 7);
            return false;
        }

        if (!method_exists(new self(), self::$_map[$source])) {
            self::setError('不存在该方法', 20002);
            return false;
        }

        return call_user_func_array(array(new self(), self::$_map[$source]), $params);
    }

}
