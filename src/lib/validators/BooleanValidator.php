<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;

use Abstracts\Validator;

class BooleanValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"必须是"{true}"或者"{false}"';
    /* @var string 被认为 "true" 的值 */
    public $trueValue = '1';
    /* @var string 被认为 "false" 的值 */
    public $falseValue = '0';
    /* @var bool 是否执行严格验证 */
    public $strict = false;

    /**
     * 验证是否为符合规则的 "boolean" 值，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
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
        if (
            (!$this->strict && $value != $this->trueValue && $value != $this->falseValue)
            || ($this->strict && $value !== $this->trueValue && $value !== $this->falseValue)
        ) {
            $this->addError($object, $attribute, $this->message, [
                '{true}' => $this->trueValue,
                '{false}' => $this->falseValue,
            ]);
        }
    }
}