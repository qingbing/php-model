<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;


use Helper\Exception;
use Model\Validator;

class RegularExpressionValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"无效';
    /* @var string 验证的正则表达式 */
    public $pattern;
    /* @var bool 是否反转验证逻辑 */
    public $not = false;

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
        if (null === $this->pattern) {
            throw new Exception('验证正则表达式无效', 101100501);
        }
        // 正则表达式验证
        if (
            is_array($value)
            || (!$this->not && !preg_match($this->pattern, $value))
            || ($this->not && preg_match($this->pattern, $value))
        ) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}