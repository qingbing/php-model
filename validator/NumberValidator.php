<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class NumberValidator extends Validator
{
    public $integerOnly = false; // 是否限定为整数
    public $max; // 允许的最大值
    public $min; // 允许的最小值
    public $tooBigMessage = '"{attribute}" is too big (maximum is {max}).'; // 超过最大值时提示的错误信息
    public $tooSmallMessage = '"{attribute}" is too small (minimum is {min}).'; // 不及最小值时提示的错误信息
    public $integerPattern = '/^\s*[+-]?\d+\s*$/'; // 整数的正则表达式
    public $numberPattern = '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'; // 浮点数的正则表达式

    /**
     * 安全规则，不需要做任何验证规则
     * @param \pf\core\Model $object
     * @param $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (!is_numeric($value)) {
            $message = $this->message !== null ? $this->message : '"{attribute}" must be a number.';
            $this->addError($object, $attribute, $message);
            return;
        }
        if ($this->integerOnly) {
            if (!preg_match($this->integerPattern, "$value")) {
                $message = $this->message !== null ? $this->message : '"{attribute}" must be an integer.';
                $this->addError($object, $attribute, $message);
                return;
            }
        } else if (!preg_match($this->numberPattern, "$value")) {
            $message = null !== $this->message ? $this->message : '"{attribute}" must be a number.';
            $this->addError($object, $attribute, $message);
            return;
        }
        if (null !== $this->min && $value < $this->min) {
            $this->addError($object, $attribute, $this->tooSmallMessage, ['{min}' => $this->min]);
            return;
        }
        if (null !== $this->max && $value > $this->max) {
            $this->addError($object, $attribute, $this->tooBigMessage, ['{max}' => $this->max]);
        }
    }
}