<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;

use Model\Validator;

defined('APP_CHARSET') or define('APP_CHARSET', 'utf-8');

class StringValidator extends Validator
{
    /* @var integer 允许的最大长度 */
    public $maxLength;
    /* @var integer 允许的最小长度 */
    public $minLength;
    /* @var integer 指定字符串的长度 */
    public $length;
    /* @var string 超过允许最大长度的提示信息 */
    public $tooLongMessage = '"{attribute}"最大允许长度为"{maxLength}"';
    /* @var string 不及允许最小长度的提示信息 */
    public $tooShortMessage = '"{attribute}"最小允许长度为"{minLength}"';
    /* @var string 字符串编码规则 */
    public $encoding;

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
        if (is_array($value)) {
            $this->addError($object, $attribute, '"{attribute}"无效');
            return;
        }
        // 长度判断
        if (null !== $this->maxLength || null !== $this->minLength || null !== $this->length) {
            if (function_exists('mb_strlen') && false !== $this->encoding) {
                $length = mb_strlen($value, $this->encoding ? $this->encoding : APP_CHARSET);
            } else {
                $length = strlen($value);
            }
            if (null !== $this->length && $length !== $this->length) {
                $message = null !== $this->message ? $this->message : '"{attribute}"必须为长度为{length}的字符串';
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