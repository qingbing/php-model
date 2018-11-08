<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;

use Model\Validator;

class EmailValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的邮箱地址';
    /* @var string 常规 email 格式的正则表达式 */
    public $pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
    /* @var string 带有用户名的 email 格式的正则表达式 */
    public $fullPattern = '/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';
    /* @var bool 是否允许 email 带用户名，eg："phpcorner <phpcorner@163.com>" */
    public $allowName = false;

    /**
     * Validates a static value to see if it is a valid email.
     * @param mixed $value
     * @return bool
     */
    public function validateValue($value)
    {
        // make sure string length is limited to avoid DOS attacks
        $valid = is_string($value) && strlen($value) <= 254 && (preg_match($this->pattern, $value) || $this->allowName && preg_match($this->fullPattern, $value));
        return $valid;
    }

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Model\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (!$this->validateValue($value)) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}