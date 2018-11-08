<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace TestClass;

class TestForm extends \FormModel
{
    public $email;
    public $boolean;
    public $contact;

    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['boolean', 'boolean'],
            ['contact', 'contact'],
        ];
    }
}