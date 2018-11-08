<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace TestClass;


class LoginForm extends \FormModel
{
    /* @var string */
    public $username;
    /* @var string */
    public $password;
    /* @var string */
    public $verifyCode;

    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
            ['  username , xxx', 'email'],
//            ['password', 'password'],
//            ['password', 'authenticate'],
//            ['verifyCode', 'string'],
//            ['verifyCode', 'captcha', 'captchaAction' => 'captcha'],
        ];
    }
}