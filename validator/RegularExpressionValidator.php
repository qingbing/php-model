<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\Unit;

class RegularExpressionValidator extends Validator
{
    public $message = '"{attribute}" is invalid.';
    public $pattern; // 验证的正则表达式
    public $not = false; // 是否反转验证逻辑

    /**
     * 正则表达式验证
     * @param \pf\core\Model $object
     * @param string $attribute
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (null === $this->pattern) {
            throw new Exception(Unit::replace('The "pattern" property must be specified with a valid regular expression.'));
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