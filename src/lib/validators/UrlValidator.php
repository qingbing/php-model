<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;

use Abstracts\Validator;

class UrlValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"是无效URL';
    /* @var string "url" 验证的正则表达式 */
    public $pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
    /* @var array "url" 支持的 scheme */
    public $validSchemes = ['http', 'https'];
    /* @var string 默认的 scheme */
    public $defaultScheme;

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
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
        if (false !== ($value = $this->validateValue($value))) {
            $object->{$attribute} = $value;
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