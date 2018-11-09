<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;

use Model\Validator;

abstract class PregValidator extends Validator
{
    /* @var string 正则表达式 */
    public $pattern;

    /**
     * 通过正则表达式验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
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
        if (false !== ($value = $this->validateValue($value))) {
            $object->{$attribute} = $value;
        } else {
            $this->addError($object, $attribute, $this->message);
        }
    }

    /**
     * 获取合法的验证值，获取失败返回"false"
     * @param string $value
     * @return bool|string
     */
    public function validateValue($value)
    {
        if (preg_match($this->pattern, $value)) {
            return $value;
        }
        return false;
    }
}