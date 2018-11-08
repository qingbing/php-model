<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\PFBase;

class StringValidator extends Validator
{
    public $maxLength; // 最大长度
    public $minLength; // 最小长度
    public $length; // 特定长度
    public $tooLongMessage = '"{attribute}" is too long (maximum is {maxLength} characters).'; // 过长时显示信息
    public $tooShortMessage = '"{attribute}" is too short (minimum is {minLength} characters).'; // 过短时显示信息
    public $encoding; // 字符串编码规则

    /**
     * 字符串相关规则验证
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
        if (is_array($value)) {
            $this->addError($object, $attribute, '"{attribute}" is invalid.');
            return;
        }
        // 长度判断
        if (null !== $this->maxLength || null !== $this->minLength || null !== $this->length) {
            if (function_exists('mb_strlen') && false !== $this->encoding) {
                $length = mb_strlen($value, $this->encoding ? $this->encoding : PFBase::app()->charset);
            } else {
                $length = strlen($value);
            }
            if (null !== $this->length && $length !== $this->length) {
                $message = null !== $this->message ? $this->message : '"{attribute}" is of the wrong length (should be {length} characters).';
                $this->addError($object, $attribute, $message, [
                    '{length}' => $this->length,
                ]);
                return;
            }
            if (null !== $this->minLength && $length < $this->minLength) {
                $this->addError($object, $attribute, $this->tooShortMessage, [
                    '{minLength}' => $this->minLength,
                ]);
            }
            if (null !== $this->maxLength && $length > $this->maxLength) {
                $this->addError($object, $attribute, $this->tooLongMessage, [
                    '{maxLength}' => $this->maxLength,
                ]);
            }
        }
    }
}