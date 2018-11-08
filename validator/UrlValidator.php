<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class UrlValidator extends Validator
{
    public $message = '"{attribute}" is not a valid URL.';
    public $pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i'; // "url" 验证的正则表达式
    public $validSchemes = ['http', 'https']; // "url" 支持的 scheme
    public $defaultScheme; // 默认的 scheme

    /**
     * "url"相关规则验证
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
        if (false !== ($value = $this->validateValue($value))) {
            $object->$attribute = $value;
        } else {
            $this->addError($object, $attribute, $this->message);
        }
    }

    /**
     * 验证 "url",验证失败返回"false"
     * @param string $value
     * @return string|false
     */
    public function validateValue($value)
    {
        if (is_string($value) && strlen($value) < 2000) {
            // make sure the length is limited to avoid DOS attacks
            if ($this->defaultScheme !== null && false === strpos($value, '://')) {
                $value = $this->defaultScheme . '://' . $value;
            }

            if (false !== strpos($this->pattern, '{schemes}')) {
                $pattern = str_replace('{schemes}', '(' . implode('|', $this->validSchemes) . ')', $this->pattern);
            } else {
                $pattern = $this->pattern;
            }

            if (preg_match($pattern, $value)) {
                return $value;
            }
        }
        return false;
    }
}