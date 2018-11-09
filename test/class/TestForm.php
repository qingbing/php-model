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
    public $date;
    public $datetime;
    public $defaultValue;
    public $fax;
    public $ip;
    public $mobile;
    public $numerical;
    public $password;
    public $phone;
    public $required;
    public $in;
    public $string;
    public $type;
    public $match;

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
            ['date', 'date'],
            ['datetime', 'datetime'],
            ['defaultValue', 'default', 'value' => 'defaultV'],
            ['fax', 'fax'],
            ['ip', 'ip'],
            ['mobile', 'mobile'],
            ['numerical', 'numerical', 'integerOnly' => true, 'max' => 15, 'min' => 5],
            ['password', 'password'],
            ['phone', 'phone'],
            ['required', 'required'],
            ['in', 'in', 'range' => ['apple', 'pear', 'banana']],
            ['string', 'string', 'maxLength' => 10, 'minLength' => 5],
            ['type', 'datetime'],
            ['match', 'match', 'pattern' => '/^\d{2,6}$/'],
        ];
    }
}